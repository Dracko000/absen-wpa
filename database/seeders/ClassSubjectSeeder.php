<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\User;

class ClassSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample subjects
        $math = Subject::create(['name' => 'Mathematics', 'code' => 'MATH101']);
        $science = Subject::create(['name' => 'Science', 'code' => 'SCI101']);
        $english = Subject::create(['name' => 'English', 'code' => 'ENG101']);
        
        // Create sample classes
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::where('role', 'super_admin')->first();
        }
        
        if ($admin) {
            $class1 = ClassModel::create([
                'name' => 'Math 101',
                'teacher_id' => $admin->id
            ]);
            
            $class2 = ClassModel::create([
                'name' => 'Science 101', 
                'teacher_id' => $admin->id
            ]);
            
            // Create sample schedules
            Schedule::create([
                'class_model_id' => $class1->id,
                'subject_id' => $math->id,
                'date' => now()->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'status' => 'active'
            ]);
            
            Schedule::create([
                'class_model_id' => $class1->id,
                'subject_id' => $math->id,
                'date' => now()->addDay()->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'status' => 'active'
            ]);
            
            Schedule::create([
                'class_model_id' => $class2->id,
                'subject_id' => $science->id,
                'date' => now()->toDateString(),
                'start_time' => '11:00:00',
                'end_time' => '12:00:00',
                'status' => 'active'
            ]);
        }
    }
}