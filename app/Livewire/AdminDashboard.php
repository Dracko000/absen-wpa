<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassModel;
use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    public $totalClasses;
    public $attendanceToday;
    public $attendanceThisWeek;
    public $upcomingSchedules;

    public function mount()
    {
        $userId = Auth::id();

        $this->totalClasses = ClassModel::where('teacher_id', $userId)->count();

        // Calculate attendance statistics for teacher's classes
        $classIds = ClassModel::where('teacher_id', $userId)->pluck('id');
        $scheduleIds = Schedule::whereIn('class_model_id', $classIds)->pluck('id');

        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();

        $this->attendanceToday = Attendance::whereIn('schedule_id', $scheduleIds)
            ->whereDate('created_at', $today)
            ->count();

        $this->attendanceThisWeek = Attendance::whereIn('schedule_id', $scheduleIds)
            ->whereDate('created_at', '>=', $thisWeek)
            ->count();

        // Get upcoming schedules for this teacher
        $this->upcomingSchedules = Schedule::whereIn('class_model_id', $classIds)
            ->where('date', '>=', $today)
            ->with('classModel', 'subject')
            ->orderBy('date', 'asc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        $userId = Auth::id();
        $classIds = ClassModel::where('teacher_id', $userId)->pluck('id');

        // Get recent attendance for teacher's classes
        $scheduleIds = Schedule::whereIn('class_model_id', $classIds)->pluck('id');
        $recentAttendances = Attendance::whereIn('schedule_id', $scheduleIds)
            ->with(['user', 'schedule.classModel', 'schedule.subject'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get all classes assigned to this teacher
        $classes = ClassModel::where('teacher_id', $userId)->get();

        return view('livewire.admin-dashboard', [
            'recentAttendances' => $recentAttendances,
            'classes' => $classes
        ]);
    }
}
