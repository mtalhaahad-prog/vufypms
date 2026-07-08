<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Proposal;
use App\Models\Milestone;
use App\Models\Document;
use App\Models\Message;
use App\Models\Announcement;
use App\Models\Evaluation;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $student  = Student::where('user_id', $user->user_id)->first();

        // Team info
        $teamMember = null;
        $team       = null;
        $teamMembers = collect();

        if ($student) {
            $teamMember = TeamMember::where('student_id', $student->student_id)->first();
            if ($teamMember) {
                $team        = Team::with('supervisor.user')->find($teamMember->team_id);
                $teamMembers = TeamMember::with('student.user')
                                ->where('team_id', $team->team_id)
                                ->get();
            }
        }

        // Proposal
        $proposal = null;
        if ($team) {
            $proposal = Proposal::where('team_id', $team->team_id)->first();
        }

        // Milestones
        $milestones   = collect();
        $doneMilestones  = 0;
        $totalMilestones = 0;
        $milestonePercent = 0;

        if ($proposal) {
            $milestones      = Milestone::where('proposal_id', $proposal->proposal_id)->get();
            $totalMilestones = $milestones->count();
            $doneMilestones  = $milestones->where('status', 'done')->count();
            $milestonePercent = $totalMilestones > 0
                ? round(($doneMilestones / $totalMilestones) * 100)
                : 0;
        }

        // Documents
        $uploadedDocs = 0;
        if ($team) {
            $uploadedDocs = Document::where('team_id', $team->team_id)->count();
        }

        // Unread Messages
        $unreadMessages = Message::where('receiver_id', $user->user_id)
                    ->where('is_read', 0)
                    ->count();

        // Latest Announcements
        $announcements = Announcement::orderBy('published_at', 'desc')->take(3)->get();

        return view('student.index', compact(
            'user', 'student', 'team', 'teamMember', 'teamMembers',
            'proposal', 'milestones', 'doneMilestones', 'totalMilestones',
            'milestonePercent', 'uploadedDocs', 'unreadMessages', 'announcements'
        ));
    }
}