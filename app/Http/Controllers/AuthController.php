<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ================================================
    // REGISTER
    // ================================================

    // Register Form Show Karo
    public function showRegister()
    {
        return view('auth.register');
    }

    // Register Form Submit Hone Par
    public function register(Request $request)
    {
        // Step 1 - Validation
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|max:150|unique:users,email',
            'password'   => 'required|string|min:6|confirmed',
            'role'       => 'required|in:student,supervisor',
            'vuid'       => 'required_if:role,student|nullable|string|max:20|unique:students,vuid',
            'program'    => 'required_if:role,student|nullable|string|max:100',
            'semester'   => 'required_if:role,student|nullable|integer|min:1|max:8',
            'department' => 'required_if:role,supervisor|nullable|string|max:100',
            'max_teams'  => 'required_if:role,supervisor|nullable|integer|min:1',
        ]);

        // Step 2 - Pehle Users Table Mein Save Karo
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Step 3 - Role Ke Hisab Se Alag Table Mein Save Karo
        if ($request->role === 'student') {

            // Students Table Mein Save Karo
            Student::create([
                'user_id'  => $user->user_id,
                'vuid'     => $request->vuid,
                'program'  => $request->program,
                'semester' => $request->semester,
            ]);

        } elseif ($request->role === 'supervisor') {

            // Supervisors Table Mein Save Karo
            Supervisor::create([
                'user_id'    => $user->user_id,
                'department' => $request->department,
                'max_teams'  => $request->max_teams ?? 5,
            ]);

        }

        // Step 4 - Register Hone Ke Baad Auto Login
        Auth::login($user);

        // Step 5 - Role Ke Hisab Se Dashboard Par Bhejo
        return $this->redirectByRole($user->role);
    }

    // ================================================
    // LOGIN
    // ================================================

    // Login Form Show Karo
    public function showLogin()
    {
        return view('auth.login');
    }

    // Login Form Submit Hone Par
    public function login(Request $request)
    {
        // Step 1 - Validation
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Step 2 - Email Aur Password Check Karo
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Step 3 - Login Successful
            $request->session()->regenerate();

            // Step 4 - Role Ke Hisab Se Dashboard Par Bhejo
            return $this->redirectByRole(Auth::user()->role);
        }

        // Step 5 - Login Failed
        return back()->withErrors([
            'email' => 'Email ya password galat hai.',
        ])->onlyInput('email');
    }

    // ================================================
    // LOGOUT
    // ================================================

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ================================================
    // PRIVATE HELPER - Role Ke Hisab Se Redirect
    // ================================================

    private function redirectByRole(string $role)
    {
        return match($role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'supervisor' => redirect()->route('supervisor.dashboard'),
            default      => redirect()->route('student.dashboard'),
        };
    }
}