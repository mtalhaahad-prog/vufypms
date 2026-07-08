<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\TeamMember;
use App\Models\Team;
use App\Models\Message;

class MessageController extends Controller
{
    // ================================================
    // PRIVATE HELPER - Student Ki Team (Supervisor Ke Saath)
    // ================================================
    private function getStudentTeam()
    {
        $user    = Auth::user();
        $student = Student::where('user_id', $user->user_id)->first();

        if (!$student) {
            return null;
        }

        $teamMember = TeamMember::where('student_id', $student->student_id)->first();

        if (!$teamMember) {
            return null;
        }

        return Team::with('supervisor.user')->find($teamMember->team_id);
    }

    // ================================================
    // MESSAGES PAGE SHOW KARO
    // ================================================
    public function index()
    {
        $user = Auth::user();
        $team = $this->getStudentTeam();

        $messages   = collect();
        $supervisor = null;

        if ($team && $team->supervisor && $team->supervisor->user) {
            $supervisor   = $team->supervisor;
            $supervisorId = $supervisor->user->user_id;

            // Student aur Supervisor ke darmiyan saari conversation lao
            // (dono taraf ke messages - purane pehle taake chat ka flow bane)
            $messages = Message::where(function ($q) use ($user, $supervisorId) {
                            $q->where('sender_id', $user->user_id)
                              ->where('receiver_id', $supervisorId);
                        })
                        ->orWhere(function ($q) use ($user, $supervisorId) {
                            $q->where('sender_id', $supervisorId)
                              ->where('receiver_id', $user->user_id);
                        })
                        ->orderBy('sent_at', 'asc')
                        ->get();

            // Page khulte hi supervisor ke bheje hue messages READ mark karo
            Message::where('sender_id', $supervisorId)
                ->where('receiver_id', $user->user_id)
                ->where('is_read', 0)
                ->update(['is_read' => 1]);
        }

        return view('student.messages', compact('team', 'supervisor', 'messages'));
    }

    // ================================================
    // NAYA MESSAGE BHEJO
    // ================================================
    public function store(Request $request)
    {
        $user = Auth::user();
        $team = $this->getStudentTeam();

        // Supervisor assigned hona chahiye
        if (!$team || !$team->supervisor || !$team->supervisor->user) {
            return redirect()->route('student.messages')
                ->with('error', 'No supervisor assigned to your team yet.');
        }

        // Validation
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        // Message save karo
        Message::create([
            'sender_id'   => $user->user_id,
            'receiver_id' => $team->supervisor->user->user_id,
            'team_id'     => $team->team_id,
            'content'     => $validated['content'],
            'is_read'     => 0,
        ]);

        return redirect()->route('student.messages');
    }
}