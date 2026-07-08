<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Supervisor;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Proposal;
use App\Models\Milestone;
use App\Models\Presentation;

class SupervisorDashboardController extends Controller
{
    // ================================================
    // SUPERVISOR DASHBOARD SHOW KARO
    // ================================================
    public function index()
    {
        $user       = Auth::user();
        $supervisor = Supervisor::where('user_id', $user->user_id)->first();

        // Default values
        $teams            = collect();
        $pendingProposals = collect();
        $upcomingDefenses = collect();
        $totalStudents    = 0;

        if ($supervisor) {
            // ── Meri assigned teams ──
            $teams = Team::where('supervisor_id', $supervisor->supervisor_id)->get();
            $teamIds = $teams->pluck('team_id');

            // ── In teams ke total students ──
            $totalStudents = TeamMember::whereIn('team_id', $teamIds)->count();

            // ── Pending proposals (review ke intezar mein) ──
            $pendingProposals = Proposal::whereIn('team_id', $teamIds)
                                    ->where('status', 'pending')
                                    ->orderBy('submitted_at', 'asc')
                                    ->get();

            // ── Aane wali presentations ──
            $upcomingDefenses = Presentation::whereIn('team_id', $teamIds)
                                    ->where('status', 'scheduled')
                                    ->where('scheduled_at', '>=', now())
                                    ->orderBy('scheduled_at', 'asc')
                                    ->take(5)
                                    ->get();
        }

        // ── Har team ka progress % calculate karo ──
        // (team => milestones done / total)
        $teamProgress = [];
        foreach ($teams as $team) {
            $proposal = Proposal::where('team_id', $team->team_id)->first();
            $percent  = 0;

            if ($proposal) {
                $total = Milestone::where('proposal_id', $proposal->proposal_id)->count();
                $done  = Milestone::where('proposal_id', $proposal->proposal_id)
                            ->where('status', 'done')
                            ->count();
                $percent = $total > 0 ? round(($done / $total) * 100) : 0;
            }

            $teamProgress[$team->team_id] = [
                'team'     => $team,
                'proposal' => $proposal,
                'percent'  => $percent,
            ];
        }

        // Team names map (pending proposals aur defenses tables ke liye)
        $teamNames = $teams->pluck('team_name', 'team_id');

        return view('supervisor.dashboard', compact(
            'supervisor', 'teams', 'totalStudents',
            'pendingProposals', 'upcomingDefenses',
            'teamProgress', 'teamNames'
        ));
    }
}