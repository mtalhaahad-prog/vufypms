<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\TeamMember;
use App\Models\Team;

class ProfileController extends Controller
{
    // Notification settings ki list (Settings page ke liye)
    const NOTIFICATION_OPTIONS = [
        'proposal'      => ['label' => 'Proposal Updates',    'desc' => 'Get notified when proposal status changes'],
        'milestones'    => ['label' => 'Milestone Reminders', 'desc' => 'Get reminders before deadlines'],
        'messages'      => ['label' => 'New Messages',        'desc' => 'Notify when supervisor sends a message'],
        'announcements' => ['label' => 'Announcements',       'desc' => 'Notify about new admin announcements'],
        'evaluations'   => ['label' => 'Evaluation Results',  'desc' => 'Notify when marks are entered'],
    ];

    // ================================================
    // PRIVATE HELPER - Student + Team Info
    // ================================================
    private function getProfileData()
    {
        $user    = Auth::user();
        $student = Student::where('user_id', $user->user_id)->first();

        $team = null;
        if ($student) {
            $teamMember = TeamMember::where('student_id', $student->student_id)->first();
            if ($teamMember) {
                $team = Team::find($teamMember->team_id);
            }
        }

        return [$user, $student, $team];
    }

    // ================================================
    // PROFILE PAGE SHOW KARO
    // ================================================
    public function index()
    {
        [$user, $student, $team] = $this->getProfileData();

        return view('student.profile', compact('user', 'student', 'team'));
    }

    // ================================================
    // PROFILE UPDATE KARO (Name + Email)
    // ================================================
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation - email unique hona chahiye, lekin apna current email allowed hai
        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $user->user_id . ',user_id',
        ]);

        User::where('user_id', $user->user_id)->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    // ================================================
    // PASSWORD CHANGE KARO
    // ================================================
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validation - confirmed ka matlab: new_password_confirmation field match kare
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        // Pehle current password verify karo
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        // Naya password save karo (hash kar ke)
        User::where('user_id', $user->user_id)->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    // ================================================
    // SETTINGS PAGE SHOW KARO
    // ================================================
    public function settings()
    {
        $user = Auth::user();

        // JSON se preferences nikalo (agar kabhi save nahi kiye to sab ON default)
        $prefs = $user->notification_prefs
                    ? json_decode($user->notification_prefs, true)
                    : array_fill_keys(array_keys(self::NOTIFICATION_OPTIONS), true);

        return view('student.settings', [
            'user'    => $user,
            'prefs'   => $prefs,
            'options' => self::NOTIFICATION_OPTIONS,
        ]);
    }

    // ================================================
    // SETTINGS SAVE KARO (Name + Email + Notifications)
    // ================================================
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $user->user_id . ',user_id',
        ]);

        // Har notification option check karo - checkbox aaya to true, warna false
        $prefs = [];
        foreach (array_keys(self::NOTIFICATION_OPTIONS) as $key) {
            $prefs[$key] = $request->has('notify_' . $key);
        }

        User::where('user_id', $user->user_id)->update([
            'name'               => $validated['name'],
            'email'              => $validated['email'],
            'notification_prefs' => json_encode($prefs),
        ]);

        return back()->with('success', 'Settings saved successfully!');
    }
}