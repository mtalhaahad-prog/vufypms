<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementController extends Controller
{
    // ================================================
    // SAARI ANNOUNCEMENTS DIKHAO (nayi pehle)
    // ================================================
    public function index()
    {
        $announcements = Announcement::orderBy('published_at', 'desc')->get();

        // Har announcement ke admin ka naam lao
        // (chhoti list hai is liye simple approach theek hai)
        $adminNames = User::whereIn('user_id', $announcements->pluck('admin_id'))
                        ->pluck('name', 'user_id');

        return view('student.announcements', compact('announcements', 'adminNames'));
    }
}