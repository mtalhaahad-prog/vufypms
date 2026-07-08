<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supervisor;
use App\Models\Team;
use App\Models\Evaluation;

class SupervisorEvaluationController extends Controller
{
    // Teeno phases (DB enum ke exact values)
    const PHASES = [
        'proposal_defense' => 'Proposal Defense',
        'progress_review'  => 'Progress Review',
        'final_defense'    => 'Final Defense',
    ];

    // ================================================
    // PRIVATE HELPER
    // ================================================
    private function getSupervisorAndTeams()
    {
        $user       = Auth::user();
        $supervisor = Supervisor::where('user_id', $user->user_id)->first();

        if (!$supervisor) {
            return [null, collect()];
        }

        $teams = Team::where('supervisor_id', $supervisor->supervisor_id)->get();

        return [$supervisor, $teams];
    }

    // ================================================
    // EVALUATIONS PAGE
    // ================================================
    public function index()
    {
        [$supervisor, $teams] = $this->getSupervisorAndTeams();

        // Meri teams ki saari evaluations
        $evaluations = Evaluation::whereIn('team_id', $teams->pluck('team_id'))->get();

        // Grid banane ke liye: [team_id][phase] => evaluation
        $grid = [];
        foreach ($evaluations as $eval) {
            $grid[$eval->team_id][$eval->phase] = $eval;
        }

        return view('supervisor.evaluations', [
            'teams'  => $teams,
            'grid'   => $grid,
            'phases' => self::PHASES,
        ]);
    }

    // ================================================
    // MARKS SAVE KARO (naya ya update)
    // ================================================
    public function store(Request $request)
    {
        [$supervisor, $teams] = $this->getSupervisorAndTeams();

        $validated = $request->validate([
            'team_id' => 'required|integer',
            'phase'   => 'required|in:proposal_defense,progress_review,final_defense',
            'marks'   => 'required|integer|min:0|max:100',
            'remarks' => 'nullable|string|max:1000',
        ]);

        // Security: sirf apni team ki evaluation
        if (!$teams->pluck('team_id')->contains($validated['team_id'])) {
            return back()->with('error', 'This team is not under your supervision.');
        }

        // Agar is team+phase ki evaluation pehle se hai to UPDATE, warna CREATE
        $existing = Evaluation::where('team_id', $validated['team_id'])
                        ->where('phase', $validated['phase'])
                        ->first();

        if ($existing) {
            $existing->update([
                'marks'   => $validated['marks'],
                'remarks' => $validated['remarks'] ?? null,
            ]);
            $msg = 'Evaluation updated successfully.';
        } else {
            Evaluation::create([
                'team_id'       => $validated['team_id'],
                'supervisor_id' => $supervisor->supervisor_id,
                'phase'         => $validated['phase'],
                'marks'         => $validated['marks'],
                'remarks'       => $validated['remarks'] ?? null,
            ]);
            $msg = 'Evaluation recorded successfully.';
        }

        return back()->with('success', $msg);
    }
}