<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Message;
use App\Models\Announcement;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ================================================
        // VIEW COMPOSER - Student Layout Ke Liye
        // Jab bhi student.layouts.app render ho,
        // ye variables automatically usko mil jayen ge
        // ================================================
        View::composer('student.layouts.app', function ($view) {

            $user = Auth::user();

            $student        = null;
            $unreadMessages = 0;
            $announcements  = collect();

            if ($user) {
                // Sidebar ke liye student info
                $student = Student::where('user_id', $user->user_id)->first();

                // Messages badge ke liye count
                $unreadMessages = Message::where('receiver_id', $user->user_id)
                    ->where('is_read', 0)
                    ->count();

                // Notification dropdown ke liye latest 3 announcements
                $announcements = Announcement::orderBy('published_at', 'desc')->take(3)->get();
            }

            $view->with(compact('student', 'unreadMessages', 'announcements'));
        });

        // ================================================
        // VIEW COMPOSER - Supervisor Layout Ke Liye
        // Sidebar badges: pending proposals + unread messages
        // ================================================
        View::composer('supervisor.layouts.app', function ($view) {

            $user = Auth::user();

            $pendingCount = 0;
            $supUnread    = 0;

            if ($user) {
                $supervisor = \App\Models\Supervisor::where('user_id', $user->user_id)->first();

                if ($supervisor) {
                    // Meri teams ke pending proposals
                    $teamIds = \App\Models\Team::where('supervisor_id', $supervisor->supervisor_id)
                                ->pluck('team_id');

                    $pendingCount = \App\Models\Proposal::whereIn('team_id', $teamIds)
                                        ->where('status', 'pending')
                                        ->count();
                }

                // Mere unread messages
                $supUnread = \App\Models\Message::where('receiver_id', $user->user_id)
                                ->where('is_read', 0)
                                ->count();
            }

            $view->with(compact('pendingCount', 'supUnread'));
        });
    }
}