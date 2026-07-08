<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Presentation;

class AdminPresentationController extends Controller
{
    const PHASES = [
        'proposal_defense' => 'Proposal Defense',
        'progress_review'  => 'Progress Review',
        'final_defense'    => 'Final Defense',
    ];

    // ================================================
    // PRESENTATIONS PAGE (schedule form + list)
    // ================================================
    public function index()
    {
        $presentations = Presentation::orderBy('scheduled_at', 'asc')->get();
        $teams         = Team::orderBy('team_name')->get();
        $teamNames     = $teams->pluck('team_name', 'team_id');

        return view('admin.presentations', [
            'presentations' => $presentations,
            'teams'         => $teams,
            'teamNames'     => $teamNames,
            'phases'        => self::PHASES,
        ]);
    }

    // ================================================
    // NAYI PRESENTATION SCHEDULE KARO
    // ================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id'       => 'required|exists:teams,team_id',
            'phase'         => 'required|in:proposal_defense,progress_review,final_defense',
            'scheduled_at'  => 'required|date|after:now',
            'venue_or_link' => 'nullable|string|max:255',
            'panel_info'    => 'nullable|string|max:255',
        ]);

        // Duplicate check: is team ki is phase ki scheduled presentation pehle se to nahi?
        $exists = Presentation::where('team_id', $validated['team_id'])
                    ->where('phase', $validated['phase'])
                    ->where('status', 'scheduled')
                    ->exists();

        if ($exists) {
            return back()->with('error', 'This team already has a scheduled presentation for this phase. Update or cancel it first.');
        }

        Presentation::create([
            'team_id'       => $validated['team_id'],
            'phase'         => $validated['phase'],
            'scheduled_at'  => $validated['scheduled_at'],
            'venue_or_link' => $validated['venue_or_link'] ?? null,
            'panel_info'    => $validated['panel_info'] ?? null,
            'status'        => 'scheduled',
        ]);

        return redirect()->route('admin.presentations')
            ->with('success', 'Presentation scheduled! The team can see it on their portal.');
    }

    // ================================================
    // STATUS UPDATE KARO (completed / cancelled)
    // ================================================
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $presentation = Presentation::find($id);

        if (!$presentation) {
            return back()->with('error', 'Presentation not found.');
        }

        $presentation->update(['status' => $validated['status']]);

        return back()->with('success', 'Presentation status updated.');
    }
}