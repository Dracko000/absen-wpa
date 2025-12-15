<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\ClassModel;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $schedules = collect();
        if ($user->role === 'admin') {
            // Get schedules for classes taught by this admin
            $classIds = ClassModel::where('teacher_id', $user->id)->pluck('id');
            $schedules = Schedule::whereIn('class_model_id', $classIds)->with('classModel', 'subject')->get();
        } elseif ($user->role === 'superadmin') {
            $schedules = Schedule::with('classModel', 'subject')->get();
        }

        return view('schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $classes = collect();
        $subjects = Subject::all();

        if ($user->role === 'admin') {
            // Only show classes taught by this admin
            $classes = ClassModel::where('teacher_id', $user->id)->get();
        } elseif ($user->role === 'superadmin') {
            $classes = ClassModel::all();
        }

        return view('schedules.create', compact('classes', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $validator = Validator::make($request->all(), [
            'class_model_id' => 'required|exists:class_models,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Additional validation: Check if class belongs to user (for admins)
        if ($user->role === 'admin') {
            $class = ClassModel::findOrFail($request->class_model_id);
            if ($class->teacher_id !== $user->id) {
                abort(403, 'Unauthorized to create schedule for this class');
            }
        }

        Schedule::create([
            'class_model_id' => $request->class_model_id,
            'subject_id' => $request->subject_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'active' // Default status
        ]);

        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        // Check if admin can access this schedule
        if ($user->role === 'admin') {
            $class = $schedule->classModel;
            if (!$class || $class->teacher_id !== $user->id) {
                abort(403, 'Unauthorized access to this schedule');
            }
        }

        $classes = collect();
        $subjects = Subject::all();

        if ($user->role === 'admin') {
            $classes = ClassModel::where('teacher_id', $user->id)->get();
        } elseif ($user->role === 'superadmin') {
            $classes = ClassModel::all();
        }

        return view('schedules.edit', compact('schedule', 'classes', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        // Check if admin can access this schedule
        if ($user->role === 'admin') {
            $class = $schedule->classModel;
            if (!$class || $class->teacher_id !== $user->id) {
                abort(403, 'Unauthorized to update this schedule');
            }
        }

        $validator = Validator::make($request->all(), [
            'class_model_id' => 'required|exists:class_models,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Additional validation for admin: ensure they can only update their classes
        if ($user->role === 'admin') {
            $class = ClassModel::findOrFail($request->class_model_id);
            if ($class->teacher_id !== $user->id) {
                abort(403, 'Unauthorized to update schedule for this class');
            }
        }

        $schedule->update([
            'class_model_id' => $request->class_model_id,
            'subject_id' => $request->subject_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        // Check if admin can access this schedule
        if ($user->role === 'admin') {
            $class = $schedule->classModel;
            if (!$class || $class->teacher_id !== $user->id) {
                abort(403, 'Unauthorized access to this schedule');
            }
        }

        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}