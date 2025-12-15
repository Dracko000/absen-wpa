<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Schedule;
use App\Models\ClassModel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping
{
    protected $classId;
    protected $fromDate;
    protected $toDate;
    protected $userId;

    public function __construct($classId, $fromDate, $toDate, $userId = null)
    {
        $this->classId = $classId;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->userId = $userId; // To check if it's an admin for access control
    }

    public function query()
    {
        $query = Attendance::query()
            ->join('schedules', 'attendances.schedule_id', '=', 'schedules.id')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->join('class_models', 'schedules.class_model_id', '=', 'class_models.id')
            ->select([
                'attendances.*',
                'users.name as student_name',
                'users.email as student_email',
                'class_models.name as class_name',
                'schedules.date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.status as schedule_status'
            ])
            ->where('schedules.class_model_id', $this->classId)
            ->whereBetween('schedules.date', [$this->fromDate, $this->toDate]);

        return $query;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Student Email',
            'Class',
            'Date',
            'Start Time',
            'End Time',
            'Check-in Time',
            'Check-out Time',
            'Status',
            'Notes',
            'Schedule Status',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->student_name,
            $attendance->student_email,
            $attendance->class_name,
            $attendance->date ? $attendance->date->format('Y-m-d') : '',
            $attendance->start_time ? $attendance->start_time->format('H:i') : '',
            $attendance->end_time ? $attendance->end_time->format('H:i') : '',
            $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '',
            $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '',
            $attendance->status,
            $attendance->notes,
            $attendance->schedule_status,
        ];
    }
}
