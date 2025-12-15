<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;

class AdminRegistrationController extends Controller
{
    /**
     * Show the admin registration form
     */
    public function showAdminRegistrationForm()
    {
        // Pre-populate the role field as 'admin'
        return view('auth.admin-register', ['default_role' => 'admin']);
    }

    /**
     * Show the superadmin registration form
     */
    public function showSuperAdminRegistrationForm()
    {
        // Pre-populate the role field as 'super_admin'
        return view('auth.admin-register', ['default_role' => 'super_admin']);
    }

    /**
     * Handle admin registration request
     */
    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // Always set role as admin
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Admin registration successful. Please login.');
    }

    /**
     * Handle superadmin registration request
     */
    public function registerSuperAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'super_admin', // Always set role as super_admin
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Superadmin registration successful. Please login.');
    }
}