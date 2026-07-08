<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\Proposal;
use App\Models\Announcement;

class GuestController extends Controller
{
    // ================================================
    // LANDING PAGE (home)
    // ================================================
    public function home()
    {
        // Landing page ke stats
        $stats = [
            'students' => User::where('role', 'student')->count(),
            'teams'    => Team::count(),
            'projects' => Proposal::where('status', 'approved')->count(),
        ];

        // Latest 3 announcements
        $announcements = Announcement::orderBy('published_at', 'desc')->take(3)->get();

        return view('guest.home', compact('stats', 'announcements'));
    }

    // ================================================
    // FYP GUIDELINES PAGE
    // ================================================
    public function guidelines()
    {
        return view('guest.guidelines');
    }

    // ================================================
    // PUBLIC ANNOUNCEMENTS PAGE
    // ================================================
    public function announcements()
    {
        $announcements = Announcement::orderBy('published_at', 'desc')->get();

        return view('guest.announcements', compact('announcements'));
    }

    // ================================================
    // APPROVED PROJECTS SHOWCASE (search ke saath)
    // ================================================
    public function projects(Request $request)
    {
        $query = Proposal::where('status', 'approved');

        // Search filter (?search=...)
        $search = $request->query('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%")
                  ->orWhere('tools', 'like', "%{$search}%");
            });
        }

        $projects  = $query->orderBy('submitted_at', 'desc')->get();
        $teamNames = Team::whereIn('team_id', $projects->pluck('team_id'))
                        ->pluck('team_name', 'team_id');

        return view('guest.projects', compact('projects', 'teamNames', 'search'));
    }
}