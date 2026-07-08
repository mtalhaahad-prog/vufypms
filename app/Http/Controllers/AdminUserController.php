<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\TeamMember;
use App\Models\Team;

class AdminUserController extends Controller
{
    // ================================================
    // USERS LIST PAGE (search + role filter ke saath)
    // ================================================
    public function index(Request $request)
    {
        $query = User::query();

        // Role filter (?role=student ya ?role=supervisor)
        $role = $request->query('role');
        if (in_array($role, ['student', 'supervisor', 'admin'])) {
            $query->where('role', $role);
        }

        // Search filter (?search=talha)
        $search = $request->query('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        // Har user ki extra info (VUID ya department)
        $students    = Student::whereIn('user_id', $users->pluck('user_id'))->get()->keyBy('user_id');
        $supervisors = Supervisor::whereIn('user_id', $users->pluck('user_id'))->get()->keyBy('user_id');

        return view('admin.users', compact('users', 'students', 'supervisors', 'role', 'search'));
    }

    // ================================================
    // NAYA SUPERVISOR ACCOUNT BANAO
    // (students khud register karte hain, supervisors admin banata hai)
    // ================================================
    public function storeSupervisor(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|max:150|unique:users,email',
            'password'   => 'required|string|min:6',
            'department' => 'required|string|max:100',
            'max_teams'  => 'required|integer|min:1|max:20',
        ]);

        // Pehle user banao
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'supervisor',
        ]);

        // Phir supervisor profile banao
        Supervisor::create([
            'user_id'    => $user->user_id,
            'department' => $validated['department'],
            'max_teams'  => $validated['max_teams'],
        ]);

        return redirect()->route('admin.users', ['role' => 'supervisor'])
            ->with('success', 'Supervisor account created successfully!');
    }

    // ================================================
    // USER DELETE KARO (protections ke saath)
    // ================================================
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Protection 1: Admin khud ko delete nahi kar sakta
        if ($user->user_id === Auth::user()->user_id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Protection 2: Doosra admin bhi delete nahi ho sakta
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin accounts cannot be deleted.');
        }

        // Protection 3: Aisa supervisor delete nahi ho sakta jiske paas teams hain
        if ($user->role === 'supervisor') {
            $sup = Supervisor::where('user_id', $user->user_id)->first();
            if ($sup && Team::where('supervisor_id', $sup->supervisor_id)->exists()) {
                return back()->with('error', 'This supervisor has assigned teams. Reassign the teams first.');
            }
        }

        // Protection 4: Aisa student delete nahi ho sakta jo kisi team ka LEADER hai
        if ($user->role === 'student') {
            $stu = Student::where('user_id', $user->user_id)->first();
            if ($stu && TeamMember::where('student_id', $stu->student_id)->where('role', 'leader')->exists()) {
                return back()->with('error', 'This student is a team leader. Transfer leadership or dissolve the team first.');
            }
        }

        // Delete karo - DB ke ON DELETE CASCADE se related records khud saaf ho jayen ge
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}