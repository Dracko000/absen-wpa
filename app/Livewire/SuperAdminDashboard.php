<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;

class SuperAdminDashboard extends Component
{
    public $totalStudents;
    public $totalTeachers;
    public $totalClasses;
    public $attendanceToday;
    public $attendanceThisWeek;
    public $attendanceThisMonth;

    public function mount()
    {
        $this->totalStudents = User::where('role', 'user')->count();
        $this->totalTeachers = User::where('role', 'admin')->count();
        $this->totalClasses = ClassModel::count();

        // Calculate attendance statistics
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $this->attendanceToday = Attendance::whereDate('created_at', $today)->count();
        $this->attendanceThisWeek = Attendance::whereDate('created_at', '>=', $thisWeek)->count();
        $this->attendanceThisMonth = Attendance::whereDate('created_at', '>=', $thisMonth)->count();
    }

    public function render()
    {
        // Get recent attendance records for all users
        $recentAttendances = Attendance::with(['user', 'schedule.classModel', 'schedule.subject'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent admin attendance records (attendance records for admin/superadmin users)
        $recentAdminAttendances = Attendance::with(['user', 'schedule.classModel', 'schedule.subject'])
            ->whereHas('user', function($query) {
                $query->whereIn('role', ['admin', 'superadmin']);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get all classes with teacher info
        $classes = ClassModel::with('teacher')->get();

        // Get class members (students) for each class
        $classMembers = [];
        foreach ($classes as $class) {
            $classMembers[$class->id] = User::where('class_id', $class->id)->where('role', 'user')->get();
        }

        return view('livewire.super-admin-dashboard', [
            'recentAttendances' => $recentAttendances,
            'recentAdminAttendances' => $recentAdminAttendances,
            'classes' => $classes,
            'classMembers' => $classMembers
        ]);
    }
}
