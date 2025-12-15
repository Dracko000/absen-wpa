<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\User;
use App\Models\Schedule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Show the reports page
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized access');
        }

        // Get classes based on user role
        $classes = collect();
        if ($user->role === 'admin') {
            $classes = ClassModel::where('teacher_id', $user->id)->get();
        } elseif ($user->role === 'superadmin') {
            $classes = ClassModel::all();
        }

        return view('reports.index', compact('classes'));
    }

    /**
     * Export attendance report to Excel
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'class_id' => 'required|exists:class_models,id',
            'date_range' => 'required|in:daily,weekly,monthly',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $className = ClassModel::findOrFail($request->class_id)->name;
        $fileName = "attendance_report_{$className}_" . date('Y-m-d_H-i-s') . ".xlsx";

        return Excel::download(
            new AttendanceExport($request->class_id, $request->from_date, $request->to_date, $user->id),
            $fileName
        );
    }

    /**
     * Export daily attendance report to Excel
     */
    public function exportDailyExcel(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'class_id' => 'required|exists:class_models,id',
            'date' => 'required|date',
        ]);

        $class = ClassModel::findOrFail($request->class_id);
        $date = $request->date; // Use the specific date provided

        // Verify admin has access to this class
        if ($user->role === 'admin' && $class->teacher_id !== $user->id) {
            abort(403, 'Unauthorized access to this class');
        }

        $fileName = "daily_attendance_report_{$class->name}_{$date}_" . date('Y-m-d_H-i-s') . ".xlsx";

        return Excel::download(
            new AttendanceExport($request->class_id, $date, $date, $user->id),
            $fileName
        );
    }

    /**
     * Export weekly attendance report to Excel
     */
    public function exportWeeklyExcel(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'class_id' => 'required|exists:class_models,id',
            'week_start_date' => 'required|date',
        ]);

        $class = ClassModel::findOrFail($request->class_id);
        $weekStartDate = $request->week_start_date;
        $weekEndDate = Carbon::parse($weekStartDate)->addDays(6)->format('Y-m-d');

        // Verify admin has access to this class
        if ($user->role === 'admin' && $class->teacher_id !== $user->id) {
            abort(403, 'Unauthorized access to this class');
        }

        $fileName = "weekly_attendance_report_{$class->name}_{$weekStartDate}_to_{$weekEndDate}_" . date('Y-m-d_H-i-s') . ".xlsx";

        return Excel::download(
            new AttendanceExport($request->class_id, $weekStartDate, $weekEndDate, $user->id),
            $fileName
        );
    }

    /**
     * Export monthly attendance report to Excel
     */
    public function exportMonthlyExcel(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'class_id' => 'required|exists:class_models,id',
            'month' => 'required|date_format:Y-m',
        ]);

        $class = ClassModel::findOrFail($request->class_id);
        $month = $request->month; // Expected format: Y-m (e.g., 2023-12)
        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Verify admin has access to this class
        if ($user->role === 'admin' && $class->teacher_id !== $user->id) {
            abort(403, 'Unauthorized access to this class');
        }

        $fileName = "monthly_attendance_report_{$class->name}_{$month}_" . date('Y-m-d_H-i-s') . ".xlsx";

        return Excel::download(
            new AttendanceExport($request->class_id, $startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d'), $user->id),
            $fileName
        );
    }

    /**
     * Export attendance report to CSV
     */
    public function exportCsv(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'class_id' => 'required|exists:class_models,id',
            'date_range' => 'required|in:daily,weekly,monthly',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $className = ClassModel::findOrFail($request->class_id)->name;
        $fileName = "attendance_report_{$className}_" . date('Y-m-d_H-i-s') . ".csv";

        return Excel::download(
            new AttendanceExport($request->class_id, $request->from_date, $request->to_date, $user->id),
            $fileName,
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    /**
     * Get attendance data for report view
     */
    public function getReportData(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'class_id' => 'required|exists:class_models,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $class = ClassModel::findOrFail($request->class_id);

        // Verify admin has access to this class
        if ($user->role === 'admin' && $class->teacher_id !== $user->id) {
            abort(403, 'Unauthorized access to this class');
        }

        // Get all students in the class (for now, we'll get all users with role 'user')
        $students = User::where('role', 'user')->get();

        // Get schedules for the class within the date range
        $schedules = Schedule::where('class_model_id', $request->class_id)
            ->whereBetween('date', [$request->from_date, $request->to_date])
            ->orderBy('date')
            ->get();

        // Get attendance records for the class within the date range
        $scheduleIds = $schedules->pluck('id');
        $attendances = Attendance::whereIn('schedule_id', $scheduleIds)
            ->with(['user', 'schedule'])
            ->get()
            ->groupBy('user_id');

        return response()->json([
            'students' => $students,
            'schedules' => $schedules,
            'attendances' => $attendances
        ]);
    }

    /**
     * Import attendance data from Excel file
     */
    public function importExcel(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'class_id' => 'required|exists:class_models,id',
        ]);

        $class = ClassModel::findOrFail($request->class_id);

        // Verify admin has access to this class
        if ($user->role === 'admin' && $class->teacher_id !== $user->id) {
            abort(403, 'Unauthorized access to this class');
        }

        try {
            $file = $request->file('file');
            $import = new \App\Imports\AttendanceImport($request->class_id);
            \Maatwebsite\Excel\Facades\Excel::import($import, $file);

            return response()->json([
                'success' => true,
                'message' => 'Attendance data imported successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error importing attendance data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        // Get classes based on user role
        $classes = collect();
        if ($user->role === 'admin') {
            $classes = ClassModel::where('teacher_id', $user->id)->get();
        } elseif ($user->role === 'superadmin') {
            $classes = ClassModel::all();
        }

        return view('reports.import', compact('classes'));
    }
}
