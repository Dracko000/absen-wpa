<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Schedule;
use App\Models\ClassModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AttendanceImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $classId;

    public function __construct($classId)
    {
        $this->classId = $classId;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Find the student by name or email
            $user = User::where('name', $row['student_name'])
                        ->orWhere('email', $row['student_email'])
                        ->where('class_id', $this->classId)
                        ->first();

            if (!$user) {
                // Skip if user is not found
                continue;
            }

            // Find or create schedule based on date
            $schedule = Schedule::firstOrCreate([
                'class_model_id' => $this->classId,
                'date' => \Carbon\Carbon::parse($row['date'])->format('Y-m-d')
            ], [
                'subject_id' => \App\Models\Subject::first()->id ?? 1,
                'start_time' => \Carbon\Carbon::parse($row['date'])->format('Y-m-d') . ' 08:00:00',
                'end_time' => \Carbon\Carbon::parse($row['date'])->format('Y-m-d') . ' 17:00:00',
                'status' => 'active'
            ]);

            // Create or update attendance record
            Attendance::updateOrCreate([
                'user_id' => $user->id,
                'schedule_id' => $schedule->id
            ], [
                'check_in_time' => $row['check_in_time'] ? \Carbon\Carbon::parse($row['date'] . ' ' . $row['check_in_time']) : null,
                'check_out_time' => $row['check_out_time'] ? \Carbon\Carbon::parse($row['date'] . ' ' . $row['check_out_time']) : null,
                'status' => $row['status'] ?? 'present',
                'notes' => $row['notes'] ?? null
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'student_name' => 'required',
            'date' => 'required|date',
            'status' => 'required|in:present,late,absent,excused,sick'
        ];
    }
}