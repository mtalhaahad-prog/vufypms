<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Models\Proposal;
use App\Models\Document;
use App\Models\Supervisor;

class AdminDashboardController extends Controller
{
    // ================================================
    // ADMIN DASHBOARD SHOW KARO
    // ================================================
    public function index()
    {
        // ── User stats ──
        $totalStudents    = User::where('role', 'student')->count();
        $totalSupervisors = User::where('role', 'supervisor')->count();

        // ── Team stats ──
        $totalTeams      = Team::count();
        $unassignedTeams = Team::whereNull('supervisor_id')->count();

        // ── Proposal stats (pie chart ke liye) ──
        $proposalStats = [
            'pending'  => Proposal::where('status', 'pending')->count(),
            'approved' => Proposal::where('status', 'approved')->count(),
            'revision' => Proposal::where('status', 'revision')->count(),
            'rejected' => Proposal::where('status', 'rejected')->count(),
        ];

        // ── Documents ──
        $totalDocuments = Document::count();

        // ── Recent registered users (list ke liye) ──
        $recentUsers = User::orderBy('created_at', 'desc')->take(6)->get();

        // ── Supervisors ki load (kitni teams har supervisor ke paas) ──
        $supervisors = Supervisor::with('user')->get();
        $supervisorLoad = [];
        foreach ($supervisors as $sup) {
            $supervisorLoad[] = [
                'name'     => $sup->user->name ?? '—',
                'assigned' => Team::where('supervisor_id', $sup->supervisor_id)->count(),
                'max'      => $sup->max_teams,
            ];
        }

        return view('admin.dashboard', compact(
            'totalStudents', 'totalSupervisors', 'totalTeams', 'unassignedTeams',
            'proposalStats', 'totalDocuments', 'recentUsers', 'supervisorLoad'
        ));
    }
}