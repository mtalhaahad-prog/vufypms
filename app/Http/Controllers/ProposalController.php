<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\TeamMember;
use App\Models\Team;
use App\Models\Proposal;

class ProposalController extends Controller
{
    // ================================================
    // PRIVATE HELPER - Logged-in Student Ki Team Lao
    // ================================================
    private function getStudentTeam()
    {
        $user    = Auth::user();
        $student = Student::where('user_id', $user->user_id)->first();

        if (!$student) {
            return [null, null];
        }

        $teamMember = TeamMember::where('student_id', $student->student_id)->first();

        if (!$teamMember) {
            return [$student, null];
        }

        $team = Team::with('supervisor.user')->find($teamMember->team_id);

        return [$student, $team];
    }

    // ================================================
    // PROPOSAL PAGE SHOW KARO
    // ================================================
    public function index()
    {
        [$student, $team] = $this->getStudentTeam();

        $proposal = null;
        if ($team) {
            $proposal = Proposal::where('team_id', $team->team_id)->first();
        }

        return view('student.proposal', compact('student', 'team', 'proposal'));
    }

    // ================================================
    // NAYA PROPOSAL SUBMIT KARO
    // ================================================
    public function store(Request $request)
    {
        [$student, $team] = $this->getStudentTeam();

        // Team ke baghair proposal nahi
        if (!$team) {
            return redirect()->route('student.proposal')
                ->with('error', 'You must be part of a team before submitting a proposal.');
        }

        // Ek team ka sirf ek proposal
        if (Proposal::where('team_id', $team->team_id)->exists()) {
            return redirect()->route('student.proposal')
                ->with('error', 'Your team has already submitted a proposal.');
        }

        // Validation
        $validated = $request->validate([
            'title'    => 'required|string|max:200',
            'domain'   => 'required|string|max:100',
            'tools'    => 'required|string|max:500',
            'abstract' => 'required|string|min:50',
        ]);

        // Save karo - DB enum ke mutabiq default status 'pending'
        Proposal::create([
            'team_id'  => $team->team_id,
            'title'    => $validated['title'],
            'domain'   => $validated['domain'],
            'tools'    => $validated['tools'],
            'abstract' => $validated['abstract'],
            'status'   => 'pending',
        ]);

        return redirect()->route('student.proposal')
            ->with('success', 'Proposal submitted successfully! It is now pending supervisor review.');
    }

    // ================================================
    // PROPOSAL UPDATE / RESUBMIT KARO
    // ================================================
    public function update(Request $request)
    {
        [$student, $team] = $this->getStudentTeam();

        if (!$team) {
            return redirect()->route('student.proposal')
                ->with('error', 'Team not found.');
        }

        $proposal = Proposal::where('team_id', $team->team_id)->first();

        if (!$proposal) {
            return redirect()->route('student.proposal')
                ->with('error', 'No proposal found to update.');
        }

        // Approved proposal edit nahi ho sakta
        if ($proposal->status === 'approved') {
            return redirect()->route('student.proposal')
                ->with('error', 'An approved proposal cannot be modified.');
        }

        // Validation
        $validated = $request->validate([
            'title'    => 'required|string|max:200',
            'domain'   => 'required|string|max:100',
            'tools'    => 'required|string|max:500',
            'abstract' => 'required|string|min:50',
        ]);

        // Update karo - resubmit hone par status wapas 'pending'
        $proposal->update([
            'title'    => $validated['title'],
            'domain'   => $validated['domain'],
            'tools'    => $validated['tools'],
            'abstract' => $validated['abstract'],
            'status'   => 'pending',
        ]);

        return redirect()->route('student.proposal')
            ->with('success', 'Proposal updated and resubmitted for review.');
    }
}
