<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'schedule_id', 'check_in_time', 'check_out_time', 'status', 'notes'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function classModel()
    {
        return $this->hasOneThrough(
            ClassModel::class,
            Schedule::class,
            'id', // Foreign key on schedules table
            'id', // Foreign key on class_models table
            'schedule_id', // Local key on attendances table
            'class_model_id' // Local key on schedules table
        );
    }
}
