<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supervisor;
use App\Models\Team;
use App\Models\Proposal;
use App\Models\Milestone;

class SupervisorProposalController extends Controller
{
    // ================================================
    // PRIVATE HELPER - Supervisor + Uski Teams Ke IDs
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
    // PROPOSALS LIST PAGE
    // ================================================
    public function index()
    {
        [$supervisor, $teamIds] = $this->getMyTeamIds();

        // Meri teams ke saare proposals (pending sab se upar, phir naye pehle)
        $proposals = Proposal::whereIn('team_id', $teamIds)
                        ->orderByRaw("FIELD(status, 'pending', 'revision', 'rejected', 'approved')")
                        ->orderBy('submitted_at', 'desc')
                        ->get();

        // Team names map
        $teams = Team::whereIn('team_id', $teamIds)->get()->keyBy('team_id');

        // Stats
        $counts = [
            'pending'  => $proposals->where('status', 'pending')->count(),
            'approved' => $proposals->where('status', 'approved')->count(),
            'rejected' => $proposals->where('status', 'rejected')->count(),
            'revision' => $proposals->where('status', 'revision')->count(),
        ];

        return view('supervisor.proposals', compact('proposals', 'teams', 'counts'));
    }

    // ================================================
    // PROPOSAL KA FAISLA KARO (Approve / Reject / Revision)
    // ================================================
    public function review(Request $request, $id)
    {
        [$supervisor, $teamIds] = $this->getMyTeamIds();

        // Security: sirf APNI team ka proposal review ho sakta hai
        $proposal = Proposal::where('proposal_id', $id)
                        ->whereIn('team_id', $teamIds)
                        ->first();

        if (!$proposal) {
            return redirect()->route('supervisor.proposals')
                ->with('error', 'Proposal not found or not under your supervision.');
        }

        // Validation - decision DB enum ke mutabiq
        $validated = $request->validate([
            'decision' => 'required|in:approved,rejected,revision',
            'remarks'  => 'nullable|string|max:1000',
        ]);

        // Reject ya Revision par remarks lazmi hain (student ko feedback chahiye)
        if (in_array($validated['decision'], ['rejected', 'revision']) && empty($validated['remarks'])) {
            return redirect()->route('supervisor.proposals')
                ->with('error', 'Remarks are required when rejecting or requesting revision.');
        }

        // Faisla save karo
        $proposal->update([
            'status'             => $validated['decision'],
            'supervisor_remarks' => $validated['remarks'] ?? null,
        ]);

        // ── BONUS: Approve hone par default milestones auto-create karo ──
        // (sirf tab jab is proposal ke milestones pehle se nahi hain)
        if ($validated['decision'] === 'approved') {
            $existing = Milestone::where('proposal_id', $proposal->proposal_id)->count();

            if ($existing === 0) {
                $defaults = [
                    ['title' => 'Requirement Analysis & SRS', 'weeks' => 2],
                    ['title' => 'System Design',              'weeks' => 4],
                    ['title' => 'Development Phase',          'weeks' => 8],
                    ['title' => 'Testing & Bug Fixing',       'weeks' => 10],
                    ['title' => 'Final Defense & Submission', 'weeks' => 12],
                ];

                foreach ($defaults as $d) {
                    Milestone::create([
                        'proposal_id' => $proposal->proposal_id,
                        'title'       => $d['title'],
                        'due_date'    => now()->addWeeks($d['weeks'])->toDateString(),
                        'status'      => 'pending',
                    ]);
                }
            }
        }

        $messages = [
            'approved' => 'Proposal approved successfully! Default milestones have been set for the team.',
            'rejected' => 'Proposal rejected. The team has been notified with your remarks.',
            'revision' => 'Revision requested. The team can now edit and resubmit their proposal.',
        ];

        return redirect()->route('supervisor.proposals')
            ->with('success', $messages[$validated['decision']]);
    }
}