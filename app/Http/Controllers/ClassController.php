<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
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

        $classes = collect();
        if ($user->role === 'admin') {
            $classes = ClassModel::where('teacher_id', $user->id)->get();
        } elseif ($user->role === 'superadmin') {
            $classes = ClassModel::all();
        }

        return view('classes.index', compact('classes'));
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

        return view('classes.create');
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

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Validate teacher_id separately to check permissions and existence
        if ($user->isSuperAdmin()) {
            $teacher = \App\Models\User::where('id', $request->teacher_id)->whereIn('role', ['admin', 'super_admin'])->first();
            if (!$teacher) {
                abort(403, 'Invalid teacher selected');
            }
            $teacher_id = $request->teacher_id;
        } else {
            // Admins can only assign themselves as teacher
            if ($request->teacher_id != $user->id) {
                abort(403, 'You can only create classes for yourself');
            }
            $teacher_id = $user->id;
        }

        $class = ClassModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $teacher_id
        ]);

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassModel $class)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        // Only allow editing classes that belong to their own if they're not superadmin
        if ($user->role === 'admin' && $class->teacher_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        return view('classes.edit', compact('class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassModel $class)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        // Only allow updating classes that belong to their own if they're not superadmin
        if ($user->role === 'admin' && $class->teacher_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassModel $class)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        // Only allow deleting classes that belong to their own if they're not superadmin
        if ($user->role === 'admin' && $class->teacher_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }

    /**
     * Helper method to get teacher IDs for superadmin validation
     */
    private function getTeacherIds()
    {
        $teacherIds = \App\Models\User::where('role', 'admin')->pluck('id')->toArray();
        return implode(',', $teacherIds);
    }
}