<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\TeamMember;
use App\Models\Team;
use App\Models\Presentation;

class PresentationController extends Controller
{
    // Teeno phases (DB enum ke exact values)
    const PHASES = [
        'proposal_defense' => ['label' => 'Proposal Defense', 'desc' => 'Present your project idea'],
        'progress_review'  => ['label' => 'Progress Review',  'desc' => 'Mid-point evaluation'],
        'final_defense'    => ['label' => 'Final Defense',    'desc' => 'Final project presentation'],
    ];

    // ================================================
    // PRESENTATIONS PAGE SHOW KARO
    // ================================================
    public function index()
    {
        $user    = Auth::user();
        $student = Student::where('user_id', $user->user_id)->first();

        $team          = null;
        $presentations = collect();

        if ($student) {
            $teamMember = TeamMember::where('student_id', $student->student_id)->first();
            if ($teamMember) {
                $team = Team::find($teamMember->team_id);
            }
        }

        if ($team) {
            $presentations = Presentation::where('team_id', $team->team_id)
                                ->orderBy('scheduled_at', 'asc')
                                ->get();
        }

        // Phase-wise mapping (Defense Phases cards ke liye)
        $byPhase = $presentations->keyBy('phase');

        return view('student.presentations', [
            'team'          => $team,
            'presentations' => $presentations,
            'byPhase'       => $byPhase,
            'phases'        => self::PHASES,
        ]);
    }
}