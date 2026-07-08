<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\TeamMember;
use App\Models\Team;
use App\Models\Proposal;
use App\Models\Milestone;
use App\Models\Document;

class MilestoneController extends Controller
{
    // ================================================
    // MILESTONES PAGE SHOW KARO
    // ================================================
    public function index()
    {
        $user    = Auth::user();
        $student = Student::where('user_id', $user->user_id)->first();

        // Default values (agar team/proposal na ho)
        $team       = null;
        $proposal   = null;
        $milestones = collect();

        // Student ki team dhoondo
        if ($student) {
            $teamMember = TeamMember::where('student_id', $student->student_id)->first();
            if ($teamMember) {
                $team = Team::find($teamMember->team_id);
            }
        }

        // Team ka proposal dhoondo
        if ($team) {
            $proposal = Proposal::where('team_id', $team->team_id)->first();
        }

        // Proposal ke milestones lao (due date ke order mein)
        if ($proposal) {
            $milestones = Milestone::where('proposal_id', $proposal->proposal_id)
                            ->orderBy('due_date', 'asc')
                            ->get();
        }

        // ── Stats calculate karo ──
        $total      = $milestones->count();
        $done       = $milestones->where('status', 'done')->count();
        $inProgress = $milestones->where('status', 'in_progress')->count();
        $pending    = $milestones->where('status', 'pending')->count();

        // Progress percentage (0 se divide na ho is liye check)
        $percent = $total > 0 ? round(($done / $total) * 100) : 0;

        // ── Har milestone ke against documents ka check ──
        // Kis kis milestone par document upload hua hai
        $docMilestoneIds = collect();
        if ($team) {
            $docMilestoneIds = Document::where('team_id', $team->team_id)
                                ->whereNotNull('milestone_id')
                                ->pluck('milestone_id');
        }

        return view('student.milestones', compact(
            'team', 'proposal', 'milestones',
            'total', 'done', 'inProgress', 'pending', 'percent',
            'docMilestoneIds'
        ));
    }
}