<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supervisor;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Message;

class SupervisorMessageController extends Controller
{
    // ================================================
    // PRIVATE HELPER - Mere Students Ki List
    // (meri teams ke saare members, unke user data ke saath)
    // ================================================
    private function getMyStudents()
    {
        $user       = Auth::user();
        $supervisor = Supervisor::where('user_id', $user->user_id)->first();

        if (!$supervisor) {
            return collect();
        }

        $teamIds = Team::where('supervisor_id', $supervisor->supervisor_id)->pluck('team_id');

        return TeamMember::with('student.user')
                    ->whereIn('team_id', $teamIds)
                    ->get();
    }

    // ================================================
    // MESSAGES PAGE (conversations list + selected chat)
    // ================================================
    public function index(Request $request)
    {
        $user     = Auth::user();
        $students = $this->getMyStudents();

        // Har student ke unread messages ka count (list mein badge ke liye)
        $unreadCounts = Message::where('receiver_id', $user->user_id)
                            ->where('is_read', 0)
                            ->selectRaw('sender_id, COUNT(*) as cnt')
                            ->groupBy('sender_id')
                            ->pluck('cnt', 'sender_id');

        // Selected student (URL: /supervisor/messages?student=3)
        $selectedId      = $request->query('student');
        $selectedStudent = null;
        $messages        = collect();

        if ($selectedId) {
            // Security: selected student meri hi kisi team ka hona chahiye
            $match = $students->first(fn($m) => $m->student->user->user_id == $selectedId);

            if ($match) {
                $selectedStudent = $match->student->user;

                // Poori conversation (dono taraf ki)
                $messages = Message::where(function ($q) use ($user, $selectedId) {
                                $q->where('sender_id', $user->user_id)
                                  ->where('receiver_id', $selectedId);
                            })
                            ->orWhere(function ($q) use ($user, $selectedId) {
                                $q->where('sender_id', $selectedId)
                                  ->where('receiver_id', $user->user_id);
                            })
                            ->orderBy('sent_at', 'asc')
                            ->get();

                // Is student ke messages read mark karo
                Message::where('sender_id', $selectedId)
                    ->where('receiver_id', $user->user_id)
                    ->where('is_read', 0)
                    ->update(['is_read' => 1]);
            }
        }

        return view('supervisor.messages', compact('students', 'unreadCounts', 'selectedStudent', 'messages'));
    }

    // ================================================
    // REPLY BHEJO
    // ================================================
    public function store(Request $request, $userId)
    {
        $user     = Auth::user();
        $students = $this->getMyStudents();

        // Security: sirf apne student ko message
        $match = $students->first(fn($m) => $m->student->user->user_id == $userId);

        if (!$match) {
            return redirect()->route('supervisor.messages')
                ->with('error', 'This student is not in your teams.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        Message::create([
            'sender_id'   => $user->user_id,
            'receiver_id' => $userId,
            'team_id'     => $match->team_id,
            'content'     => $validated['content'],
            'is_read'     => 0,
        ]);

        return redirect()->route('supervisor.messages', ['student' => $userId]);
    }
}