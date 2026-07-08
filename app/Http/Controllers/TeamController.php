<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Team;
use App\Models\TeamMember;

class TeamController extends Controller
{
    // Team mai maximum kitne members ho sakte hain
    const MAX_MEMBERS = 4;

    // ================================================
    // PRIVATE HELPER - Logged-in Student + Uski Team
    // ================================================
    private function getStudentTeam()
    {
        $user    = Auth::user();
        $student = Student::where('user_id', $user->user_id)->first();

        if (!$student) {
            return [null, null, null];
        }

        $teamMember = TeamMember::where('student_id', $student->student_id)->first();

        if (!$teamMember) {
            return [$student, null, null];
        }

        $team = Team::with('supervisor.user')->find($teamMember->team_id);

        return [$student, $team, $teamMember];
    }

    // ================================================
    // TEAM PAGE SHOW KARO
    // ================================================
    public function index()
    {
        [$student, $team, $teamMember] = $this->getStudentTeam();

        $members = collect();
        if ($team) {
            // Team ke saare members unke user data ke saath lao
            $members = TeamMember::with('student.user')
                        ->where('team_id', $team->team_id)
                        ->get();
        }

        return view('student.team', compact('student', 'team', 'teamMember', 'members'));
    }

    // ================================================
    // NAYI TEAM CREATE KARO
    // ================================================
    public function store(Request $request)
    {
        [$student, $team, $teamMember] = $this->getStudentTeam();

        // Agar pehle se team mai hai to nayi team nahi bana sakta
        if ($team) {
            return redirect()->route('student.team')
                ->with('error', 'You are already part of a team.');
        }

        // Validation
        $validated = $request->validate([
            'team_name' => 'required|string|max:100',
        ]);

        // Unique group code generate karo - e.g. S26PROJ4F2A9B
        do {
            $groupCode = 'S26PROJ' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (Team::where('group_code', $groupCode)->exists());

        // Team banao (supervisor abhi NULL - admin baad mein assign karega)
        $newTeam = Team::create([
            'team_name'  => $validated['team_name'],
            'group_code' => $groupCode,
            'status'     => 'active',
        ]);

        // Team banane wala automatically LEADER banta hai
        TeamMember::create([
            'team_id'    => $newTeam->team_id,
            'student_id' => $student->student_id,
            'role'       => 'leader',
        ]);

        return redirect()->route('student.team')
            ->with('success', 'Team created successfully! You are the team leader. Group Code: ' . $groupCode);
    }

    // ================================================
    // MEMBER ADD KARO (VUID SE) - SIRF LEADER
    // ================================================
    public function addMember(Request $request)
    {
        [$student, $team, $teamMember] = $this->getStudentTeam();

        // Team honi chahiye
        if (!$team) {
            return redirect()->route('student.team')
                ->with('error', 'You must create a team first.');
        }

        // Sirf leader member add kar sakta hai
        if ($teamMember->role !== 'leader') {
            return redirect()->route('student.team')
                ->with('error', 'Only the team leader can add members.');
        }

        // Validation
        $validated = $request->validate([
            'vuid' => 'required|string|max:20',
        ]);

        // Member limit check karo
        $currentCount = TeamMember::where('team_id', $team->team_id)->count();
        if ($currentCount >= self::MAX_MEMBERS) {
            return redirect()->route('student.team')
                ->with('error', 'Team is full. Maximum ' . self::MAX_MEMBERS . ' members allowed.');
        }

        // VUID se student dhoondo
        $newStudent = Student::where('vuid', $validated['vuid'])->first();

        if (!$newStudent) {
            return redirect()->route('student.team')
                ->with('error', 'No registered student found with VUID: ' . $validated['vuid']);
        }

        // Check karo ke wo student pehle se kisi team mein to nahi
        if (TeamMember::where('student_id', $newStudent->student_id)->exists()) {
            return redirect()->route('student.team')
                ->with('error', 'This student is already part of a team.');
        }

        // Member add karo
        TeamMember::create([
            'team_id'    => $team->team_id,
            'student_id' => $newStudent->student_id,
            'role'       => 'member',
        ]);

        return redirect()->route('student.team')
            ->with('success', 'Member added to your team successfully!');
    }

    // ================================================
    // MEMBER REMOVE KARO - SIRF LEADER
    // ================================================
    public function removeMember($id)
    {
        [$student, $team, $teamMember] = $this->getStudentTeam();

        if (!$team || $teamMember->role !== 'leader') {
            return redirect()->route('student.team')
                ->with('error', 'Only the team leader can remove members.');
        }

        $memberToRemove = TeamMember::where('id', $id)
                            ->where('team_id', $team->team_id)
                            ->first();

        if (!$memberToRemove) {
            return redirect()->route('student.team')
                ->with('error', 'Member not found in your team.');
        }

        // Leader khud ko remove nahi kar sakta
        if ($memberToRemove->role === 'leader') {
            return redirect()->route('student.team')
                ->with('error', 'The team leader cannot be removed.');
        }

        $memberToRemove->delete();

        return redirect()->route('student.team')
            ->with('success', 'Member removed from the team.');
    }
}