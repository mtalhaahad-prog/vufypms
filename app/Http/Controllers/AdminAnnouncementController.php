<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;

class AdminAnnouncementController extends Controller
{
    // ================================================
    // ANNOUNCEMENTS PAGE (list + publish form)
    // ================================================
    public function index()
    {
        $announcements = Announcement::orderBy('published_at', 'desc')->get();

        return view('admin.announcements', compact('announcements'));
    }

    // ================================================
    // NAYI ANNOUNCEMENT PUBLISH KARO
    // ================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'body'  => 'required|string|max:2000',
        ]);

        Announcement::create([
            'admin_id' => Auth::user()->user_id,
            'title'    => $validated['title'],
            'body'     => $validated['body'],
        ]);

        return redirect()->route('admin.announcements')
            ->with('success', 'Announcement published! All students can now see it.');
    }

    // ================================================
    // ANNOUNCEMENT DELETE KARO
    // ================================================
    public function destroy($id)
    {
        $announcement = Announcement::find($id);

        if (!$announcement) {
            return back()->with('error', 'Announcement not found.');
        }

        $announcement->delete();

        return back()->with('success', 'Announcement deleted.');
    }
}