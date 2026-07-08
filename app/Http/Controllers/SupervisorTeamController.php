<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Supervisor;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Proposal;
use App\Models\Milestone;
use App\Models\Document;

class SupervisorTeamController extends Controller
{
    // ================================================
    // PRIVATE HELPER - Supervisor + Uski Team IDs
    // ================================================
    private function getMyTeamIds()
    {
        $user       = Auth::user();
        $supervisor = Supervisor::where('user_id', $user->user_id)->first();

        if (!$supervisor) {
            return [null, collect()];
        }

        $teamIds = Team::where('supervisor_id', $supervisor->supervisor_id)
                        ->pluck('team_id');

        return [$supervisor, $teamIds];
    }

    // ================================================
    // MY TEAMS LIST PAGE
    // ================================================
    public function index()
    {
        [$supervisor, $teamIds] = $this->getMyTeamIds();

        $teams = Team::whereIn('team_id', $teamIds)->get();

        // Har team ki summary banao (members count, proposal status, progress %)
        $teamData = [];
        foreach ($teams as $team) {
            $membersCount = TeamMember::where('team_id', $team->team_id)->count();
            $proposal     = Proposal::where('team_id', $team->team_id)->first();

            $percent = 0;
            if ($proposal) {
                $total = Milestone::where('proposal_id', $proposal->proposal_id)->count();
                $done  = Milestone::where('proposal_id', $proposal->proposal_id)
                            ->where('status', 'done')->count();
                $percent = $total > 0 ? round(($done / $total) * 100) : 0;
            }

            $teamData[] = [
                'team'         => $team,
                'membersCount' => $membersCount,
                'proposal'     => $proposal,
                'percent'      => $percent,
            ];
        }

        return view('supervisor.teams', compact('teamData', 'supervisor'));
    }

    // ================================================
    // TEAM DETAIL PAGE
    // ================================================
    public function show($id)
    {
        [$supervisor, $teamIds] = $this->getMyTeamIds();

        // Security: sirf apni team ka detail
        if (!$teamIds->contains($id)) {
            return redirect()->route('supervisor.teams')
                ->with('error', 'Team not found or not under your supervision.');
        }

        $team    = Team::find($id);
        $members = TeamMember::with('student.user')->where('team_id', $id)->get();

        $proposal   = Proposal::where('team_id', $id)->first();
        $milestones = collect();

        if ($proposal) {
            $milestones = Milestone::where('proposal_id', $proposal->proposal_id)
                            ->orderBy('due_date', 'asc')->get();
        }

        $documents = Document::where('team_id', $id)
                        ->orderBy('uploaded_at', 'desc')->get();

        // Milestone titles map (documents table mein dikhane ke liye)
        $milestoneTitles = $milestones->pluck('title', 'milestone_id');

        return view('supervisor.team-detail', compact(
            'team', 'members', 'proposal', 'milestones', 'documents', 'milestoneTitles'
        ));
    }

    // ================================================
    // MILESTONE STATUS UPDATE KARO
    // ================================================
    public function updateMilestone(Request $request, $id)
    {
        [$supervisor, $teamIds] = $this->getMyTeamIds();

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,done',
        ]);

        $milestone = Milestone::find($id);

        if (!$milestone) {
            return back()->with('error', 'Milestone not found.');
        }

        // Security: milestone meri hi kisi team ke proposal ka hona chahiye
        $proposal = Proposal::find($milestone->proposal_id);
        if (!$proposal || !$teamIds->contains($proposal->team_id)) {
            return back()->with('error', 'This milestone does not belong to your teams.');
        }

        $milestone->update(['status' => $validated['status']]);

        return back()->with('success', 'Milestone status updated to "' . str_replace('_', ' ', $validated['status']) . '".');
    }

    // ================================================
    // TEAM KA DOCUMENT DOWNLOAD KARO
    // ================================================
    public function downloadDocument($id)
    {
        [$supervisor, $teamIds] = $this->getMyTeamIds();

        // Security: sirf apni teams ka document
        $document = Document::where('document_id', $id)
                        ->whereIn('team_id', $teamIds)
                        ->first();

        if (!$document || !Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($document->file_path);
    }
}