<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\TeamMember;
use App\Models\Team;
use App\Models\Evaluation;

class EvaluationController extends Controller
{
    // Teeno phases (DB enum ke exact values)
    const PHASES = [
        'proposal_defense' => 'Proposal Defense',
        'progress_review'  => 'Progress Review',
        'final_defense'    => 'Final Defense',
    ];

    // ================================================
    // EVALUATIONS PAGE SHOW KARO
    // ================================================
    public function index()
    {
        $user    = Auth::user();
        $student = Student::where('user_id', $user->user_id)->first();

        $team        = null;
        $evaluations = collect();

        if ($student) {
            $teamMember = TeamMember::where('student_id', $student->student_id)->first();
            if ($teamMember) {
                $team = Team::find($teamMember->team_id);
            }
        }

        if ($team) {
            $evaluations = Evaluation::where('team_id', $team->team_id)
                            ->orderBy('evaluated_at', 'desc')
                            ->get();
        }

        // Har phase ki evaluation alag karo (phase => evaluation)
        // Taake cards mein har phase ke marks dikha saken
        $byPhase = $evaluations->keyBy('phase');

        return view('student.evaluations', [
            'team'        => $team,
            'evaluations' => $evaluations,
            'byPhase'     => $byPhase,
            'phases'      => self::PHASES,
        ]);
    }
}