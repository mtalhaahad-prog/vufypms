<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Supervisor;
use App\Models\Proposal;
use App\Models\Milestone;

class AdminTeamController extends Controller
{
    // ================================================
    // SUPERVISOR ASSIGNMENT PAGE
    // ================================================
    public function assignments()
    {
        // Saari teams (unassigned sab se upar)
        $teams = Team::orderByRaw('supervisor_id IS NULL DESC')->get();

        // Supervisors + unki current load
        $supervisors = Supervisor::with('user')->get();
        $loadMap = [];
        foreach ($supervisors as $sup) {
            $loadMap[$sup->supervisor_id] = Team::where('supervisor_id', $sup->supervisor_id)->count();
        }

        // Har team ke members count
        $memberCounts = TeamMember::selectRaw('team_id, COUNT(*) as cnt')
                            ->groupBy('team_id')->pluck('cnt', 'team_id');

        return view('admin.assignments', compact('teams', 'supervisors', 'loadMap', 'memberCounts'));
    }

    // ================================================
    // TEAM KO SUPERVISOR ASSIGN KARO
    // ================================================
    public function assign(Request $request, $teamId)
    {
        $validated = $request->validate([
            'supervisor_id' => 'required|exists:supervisors,supervisor_id',
        ]);

        $team = Team::find($teamId);
        if (!$team) {
            return back()->with('error', 'Team not found.');
        }

        $supervisor = Supervisor::find($validated['supervisor_id']);

        // Capacity check: supervisor ki max_teams limit
        $currentLoad = Team::where('supervisor_id', $supervisor->supervisor_id)->count();
        if ($currentLoad >= $supervisor->max_teams && $team->supervisor_id != $supervisor->supervisor_id) {
            return back()->with('error', 'This supervisor has reached their maximum team capacity (' . $supervisor->max_teams . ').');
        }

        $team->update(['supervisor_id' => $supervisor->supervisor_id]);

        return back()->with('success', 'Supervisor assigned to "' . $team->team_name . '" successfully!');
    }

    // ================================================
    // TEAMS OVERVIEW PAGE
    // ================================================
    public function teams()
    {
        $teams = Team::with('supervisor.user')->orderBy('team_id', 'desc')->get();

        $memberCounts = TeamMember::selectRaw('team_id, COUNT(*) as cnt')
                            ->groupBy('team_id')->pluck('cnt', 'team_id');

        $proposals = Proposal::whereIn('team_id', $teams->pluck('team_id'))->get()->keyBy('team_id');

        // Har team ka progress %
        $progress = [];
        foreach ($teams as $team) {
            $p = $proposals[$team->team_id] ?? null;
            $pct = 0;
            if ($p) {
                $total = Milestone::where('proposal_id', $p->proposal_id)->count();
                $done  = Milestone::where('proposal_id', $p->proposal_id)->where('status', 'done')->count();
                $pct = $total > 0 ? round(($done / $total) * 100) : 0;
            }
            $progress[$team->team_id] = $pct;
        }

        return view('admin.teams', compact('teams', 'memberCounts', 'proposals', 'progress'));
    }
}