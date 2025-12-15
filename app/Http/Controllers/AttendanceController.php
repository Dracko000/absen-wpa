<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\User;
use App\Models\ClassModel;
use App\Services\QRCodeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected $qrCodeService;

    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Display the attendance scanning page
     */
    public function scan()
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        // Get classes taught by this teacher (for admin) or all classes (for superadmin)
        $classes = collect();
        if ($user->role === 'admin') {
            $classes = ClassModel::where('teacher_id', $user->id)->get();
        } elseif ($user->role === 'superadmin') {
            $classes = ClassModel::all();
        }

        return view('attendance.scan', compact('classes'));
    }

    /**
     * Process attendance from QR code scan
     */
    public function processScan(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        try {
            // Validate the presence of required fields
            $qr_data = $request->qr_data;
            $class_id = $request->class_id;
            $date = $request->date;

            if (!$qr_data) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code data is required'
                ]);
            }

            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Date is required for attendance'
                ]);
            }

            $decodedData = $this->qrCodeService->decodeQRData($qr_data);

            if (in_array($decodedData['type'], ['user', 'student', 'User', 'Student'])) {
                // Processing student/user QR code
                $student = User::with('class')->findOrFail($decodedData['id']);

                // Additional validation: make sure the QR code ID matches the user found
                if ($student->id != $decodedData['id']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'QR code user ID does not match system user'
                    ]);
                }

                // Use the class from the QR code data (or fallback to user's class in DB)
                $classId = $decodedData['class_id'] ?? $student->class_id;

                if (!$classId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Student does not have an assigned class. Please assign a class to the student first.'
                    ]);
                }

                // Check if this is for a specific class/schedule
                $schedule = Schedule::where('class_model_id', $classId)
                    ->where('date', $request->date)
                    ->first();

                // If no schedule exists, provide an option to create attendance anyway
                // This makes the system more flexible for impromptu attendance taking
                if (!$schedule) {
                    // To handle the foreign key constraint, we need to ensure there's at least one subject
                    $firstSubject = \App\Models\Subject::first();

                    if (!$firstSubject) {
                        // If no subjects exist, we need to handle this case differently
                        // For now, we'll return an appropriate error message to guide the user
                        return response()->json([
                            'success' => false,
                            'message' => 'No subjects found in the system. Please create at least one subject in the system before scanning attendance.',
                            'suggested_action' => 'Add a new subject in the system settings first.'
                        ]);
                    }

                    $schedule = Schedule::create([
                        'class_model_id' => $classId,
                        'subject_id' => $firstSubject->id, // Use first subject
                        'date' => $request->date,
                        'start_time' => Carbon::parse($request->date . ' 08:00'), // Default to 8 AM
                        'end_time' => Carbon::parse($request->date . ' 17:00'),   // Default to 5 PM
                        'status' => 'active'
                    ]);
                }

                // Check if attendance already exists
                $existingAttendance = Attendance::where('user_id', $student->id)
                    ->where('schedule_id', $schedule->id)
                    ->first();

                if ($existingAttendance) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Attendance already recorded for this student in this session'
                    ]);
                }

                // For better UX, we'll still auto-create attendance if on time, but provide option to change status
                $defaultStatus = now()->gt($schedule->start_time->copy()->addMinutes(15)) ? 'late' : 'present';

                // Create attendance record with default status
                $attendance = Attendance::create([
                    'user_id' => $student->id,
                    'schedule_id' => $schedule->id,
                    'check_in_time' => now(),
                    'status' => $defaultStatus
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Attendance recorded successfully',
                    'attendance' => $attendance,
                    'student' => [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'role' => $student->role,
                        'class' => $student->class?->name
                    ],
                    'requires_status_selection' => true,  // Still allow status change if needed
                    'current_status' => $defaultStatus,
                    'show_status_modal' => true  // Indicate that the status modal can be shown to change status
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR code type. Please scan a student QR code.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Record attendance manually
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        // Validate based on input type
        if ($request->has('qr_data') && $request->has('date')) {
            // Handle QR data input
            $request->validate([
                'qr_data' => 'required|string',
                'date' => 'required|date',
                'status' => 'required|in:present,late,absent,excused,sick',
                'notes' => 'nullable|string'
            ]);

            // Decode QR data to get user information
            $decodedData = $this->qrCodeService->decodeQRData($request->qr_data);

            if (!in_array($decodedData['type'], ['user', 'student', 'User', 'Student'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR code type. Please scan a student QR code.'
                ]);
            }

            $student = User::with('class')->findOrFail($decodedData['id']);
            $classId = $decodedData['class_id'] ?? $student->class_id;

            if (!$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student does not have an assigned class. Please assign a class to the student first.'
                ]);
            }

            // Check if schedule exists, if not create one
            $schedule = Schedule::where('class_model_id', $classId)
                ->where('date', $request->date)
                ->first();

            if (!$schedule) {
                // Create a temporary schedule for the purpose of recording attendance
                $firstSubject = \App\Models\Subject::first();

                if (!$firstSubject) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No subjects found in the system. Please create at least one subject in the system before scanning attendance.',
                        'suggested_action' => 'Add a new subject in the system settings first.'
                    ]);
                }

                $schedule = Schedule::create([
                    'class_model_id' => $classId,
                    'subject_id' => $firstSubject->id, // Use first subject
                    'date' => $request->date,
                    'start_time' => Carbon::parse($request->date . ' 08:00'), // Default to 8 AM
                    'end_time' => Carbon::parse($request->date . ' 17:00'),   // Default to 5 PM
                    'status' => 'active'
                ]);
            }

            // Check if attendance already exists
            $existingAttendance = Attendance::where('user_id', $student->id)
                ->where('schedule_id', $schedule->id)
                ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance already recorded for this student in this session'
                ]);
            }

            $attendance = Attendance::create([
                'user_id' => $student->id,
                'schedule_id' => $schedule->id,
                'check_in_time' => now(),
                'status' => $request->status,
                'notes' => $request->notes
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully',
                'attendance' => $attendance,
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'role' => $student->role,
                    'class' => $student->class?->name
                ]
            ]);
        } else {
            // Handle traditional user_id and schedule_id input
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'schedule_id' => 'required|exists:schedules,id',
                'status' => 'required|in:present,late,absent,excused,sick',
                'notes' => 'nullable|string'
            ]);

            $attendance = Attendance::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'schedule_id' => $request->schedule_id
                ],
                [
                    'check_in_time' => $request->check_in_time ?? now(),
                    'status' => $request->status,
                    'notes' => $request->notes
                ]
            );

            return response()->json([
                'success' => true,
                'attendance' => $attendance
            ]);
        }
    }

    /**
     * Get attendance records for a specific date and class
     */
    public function getAttendance(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'date' => 'required|date'
        ]);

        // If user_id is provided, get attendance for that specific user
        if ($request->has('user_id')) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            // If class_id is also provided, check if admin has access to that class
            if ($request->has('class_id')) {
                $request->validate([
                    'class_id' => 'required|exists:class_models,id',
                ]);

                $class = ClassModel::findOrFail($request->class_id);

                // Verify admin has access to this class
                if ($user->role === 'admin' && $class->teacher_id !== $user->id) {
                    abort(403, 'Unauthorized access to this class');
                }

                $schedule = Schedule::where('class_model_id', $request->class_id)
                    ->where('date', $request->date)
                    ->first();

                if (!$schedule) {
                    return response()->json([
                        'attendances' => [],
                        'schedule' => null
                    ]);
                }

                $attendance = Attendance::where('schedule_id', $schedule->id)
                    ->where('user_id', $request->user_id)
                    ->with('user')
                    ->get();

                return response()->json([
                    'attendances' => $attendance,
                    'schedule' => $schedule
                ]);
            } else {
                // Look for attendance in any schedule for the user on the given date
                $schedules = Schedule::where('date', $request->date)->get();

                $attendances = collect();
                foreach ($schedules as $schedule) {
                    $attendance = Attendance::where('schedule_id', $schedule->id)
                        ->where('user_id', $request->user_id)
                        ->with(['user', 'schedule.classModel'])
                        ->get();
                    $attendances = $attendances->concat($attendance);
                }

                return response()->json([
                    'attendances' => $attendances,
                    'schedules' => $schedules
                ]);
            }
        }
        // If class_id is provided, get attendance for that specific class
        else if ($request->has('class_id')) {
            $request->validate([
                'class_id' => 'required|exists:class_models,id',
            ]);

            $class = ClassModel::findOrFail($request->class_id);

            // Verify admin has access to this class
            if ($user->role === 'admin' && $class->teacher_id !== $user->id) {
                abort(403, 'Unauthorized access to this class');
            }

            $schedule = Schedule::where('class_model_id', $request->class_id)
                ->where('date', $request->date)
                ->first();

            if (!$schedule) {
                return response()->json([
                    'attendances' => [],
                    'schedule' => null
                ]);
            }

            $attendances = Attendance::where('schedule_id', $schedule->id)
                ->with('user')
                ->get();

            return response()->json([
                'attendances' => $attendances,
                'schedule' => $schedule
            ]);
        } else {
            // If no class_id is provided, fetch all attendance for the date for classes this admin can access
            $schedules = collect();
            if ($user->role === 'admin') {
                $classIds = ClassModel::where('teacher_id', $user->id)->pluck('id');
                $schedules = Schedule::whereIn('class_model_id', $classIds)
                    ->where('date', $request->date)
                    ->get();
            } elseif ($user->role === 'superadmin') {
                $schedules = Schedule::where('date', $request->date)->get();
            }

            $attendances = collect();
            foreach ($schedules as $schedule) {
                $scheduleAttendances = Attendance::where('schedule_id', $schedule->id)
                    ->with(['user', 'schedule.classModel'])
                    ->get();
                $attendances = $attendances->concat($scheduleAttendances);
            }

            return response()->json([
                'attendances' => $attendances,
                'schedules' => $schedules // Return all schedules for the date
            ]);
        }
    }

    /**
     * Show attendance history for authenticated user
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized access');
        }

        $attendances = Attendance::where('user_id', $user->id)
            ->with(['schedule.classModel', 'schedule.subject'])
            ->orderBy('created_at', 'desc')
            ->paginate(15); // Paginate for better performance

        return view('attendance.user-attendance', [
            'attendances' => $attendances,
            'user' => $user
        ]);
    }

    /**
     * Show all attendance records for admins
     */
    public function adminIndex()
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $attendances = collect();
        if ($user->role === 'admin') {
            // Get attendance records for this admin's classes
            $classIds = ClassModel::where('teacher_id', $user->id)->pluck('id');
            $scheduleIds = Schedule::whereIn('class_model_id', $classIds)->pluck('id');
            $attendances = Attendance::whereIn('schedule_id', $scheduleIds)
                ->with(['user', 'schedule.classModel', 'schedule.subject'])
                ->latest()
                ->paginate(20);
        } elseif ($user->role === 'superadmin') {
            $attendances = Attendance::with(['user', 'schedule.classModel', 'schedule.subject'])
                ->latest()
                ->paginate(20);
        }

        return view('attendance.admin-index', compact('attendances'));
    }

    /**
     * Show attendance records for a specific user
     */
    public function showUserAttendance($userId)
    {
        $authUser = Auth::user();

        if (!$authUser) {
            abort(403, 'Unauthorized access');
        }

        $user = User::findOrFail($userId);

        // Students can only see their own attendance
        if ($authUser->role === 'user' && $authUser->id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $attendances = Attendance::where('user_id', $user->id)
            ->with(['schedule.classModel', 'schedule.subject'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('attendance.user-attendance', compact('user', 'attendances'));
    }
}
