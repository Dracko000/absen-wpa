<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class StudentDashboard extends Component
{
    public $attendanceStats;
    public $recentAttendances;
    public $upcomingSchedules;
    public $availableClasses;
    public $selectedClassId;

    public function mount()
    {
        $userId = Auth::id();

        // Calculate attendance statistics for the student
        $totalAttendances = Attendance::where('user_id', $userId)->count();
        $presentCount = Attendance::where('user_id', $userId)
            ->where('status', 'present')
            ->count();
        $lateCount = Attendance::where('user_id', $userId)
            ->where('status', 'late')
            ->count();
        $absentCount = Attendance::where('user_id', $userId)
            ->where('status', 'absent')
            ->count();

        $this->attendanceStats = [
            'total' => $totalAttendances,
            'present' => $presentCount,
            'late' => $lateCount,
            'absent' => $absentCount,
            'attendance_rate' => $totalAttendances > 0 ? round(($presentCount / $totalAttendances) * 100, 2) : 0
        ];

        // Get upcoming schedules for the student's class
        $today = Carbon::today();
        $userClassId = Auth::user()->class_id;

        if ($userClassId) {
            $this->upcomingSchedules = Schedule::where('date', '>=', $today)
                ->where('class_model_id', $userClassId)
                ->with('classModel', 'subject')
                ->orderBy('date', 'asc')
                ->limit(5)
                ->get();
        } else {
            $this->upcomingSchedules = collect();
        }

        // Get available classes for the student to choose from
        $this->availableClasses = ClassModel::all();
        $this->selectedClassId = Auth::user()->class_id;
    }

    public function switchClass()
    {
        $this->validate([
            'selectedClassId' => 'nullable|exists:class_models,id'
        ]);

        $user = Auth::user();
        $user->class_id = $this->selectedClassId;
        $user->save();

        session()->flash('message', 'Class updated successfully!');

        // Refresh the upcoming schedules to match the new class
        $today = Carbon::today();
        if ($this->selectedClassId) {
            $this->upcomingSchedules = Schedule::where('date', '>=', $today)
                ->where('class_model_id', $this->selectedClassId)
                ->with('classModel', 'subject')
                ->orderBy('date', 'asc')
                ->limit(5)
                ->get();
        } else {
            $this->upcomingSchedules = collect();
        }
    }

    public function render()
    {
        $userId = Auth::id();

        // Get recent attendance records for the student
        $this->recentAttendances = Attendance::where('user_id', $userId)
            ->with(['schedule.classModel', 'schedule.subject'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.student-dashboard');
    }
}
