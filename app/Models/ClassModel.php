<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'teacher_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_model_id');
    }

    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, Schedule::class, 'class_model_id');
    }

    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'class_id');
    }
}
