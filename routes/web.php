<?php

// Guest Controller
use App\Http\Controllers\GuestController;

// ================================================
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\ProfileController;

// Supervisor Controllers
use App\Http\Controllers\SupervisorDashboardController;
use App\Http\Controllers\SupervisorProposalController;
use App\Http\Controllers\SupervisorTeamController;
use App\Http\Controllers\SupervisorEvaluationController;
use App\Http\Controllers\SupervisorMessageController;

// Admin Controller
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminTeamController;
use App\Http\Controllers\AdminAnnouncementController;
use App\Http\Controllers\AdminPresentationController;

// ── Root Redirect ──
// ── Guest / Public Routes (Step 16) ──
Route::get('/',               [GuestController::class, 'home'])->name('guest.home');
Route::get('/guidelines',     [GuestController::class, 'guidelines'])->name('guest.guidelines');
Route::get('/projects',       [GuestController::class, 'projects'])->name('guest.projects');
Route::get('/notices',        [GuestController::class, 'announcements'])->name('guest.announcements');

// ── Guest Routes (Login/Register) ──
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
});

// ── Logout ──
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ── Student Routes ──
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard',     [StudentDashboardController::class, 'index'])->name('dashboard');

        // ── Proposal (Step 2) ──
        Route::get('/proposal',      [ProposalController::class, 'index'])->name('proposal');
        Route::post('/proposal',     [ProposalController::class, 'store'])->name('proposal.store');
        Route::put('/proposal',      [ProposalController::class, 'update'])->name('proposal.update');

        // ── Team (Step 3) ──
        Route::get('/team',                 [TeamController::class, 'index'])->name('team');
        Route::post('/team',                [TeamController::class, 'store'])->name('team.store');
        Route::post('/team/add-member',     [TeamController::class, 'addMember'])->name('team.addMember');
        Route::delete('/team/member/{id}',  [TeamController::class, 'removeMember'])->name('team.remove');

        // ── Baqi pages (placeholder - aage steps mein banain ge) ──
        // ── Milestones (Step 4) ──
        Route::get('/milestones',    [MilestoneController::class, 'index'])->name('milestones');
        // ── Documents (Step 5) ──
        Route::get('/documents',                    [DocumentController::class, 'index'])->name('documents');
        Route::post('/documents',                   [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{id}/download',      [DocumentController::class, 'download'])->name('documents.download');
        Route::delete('/documents/{id}',            [DocumentController::class, 'destroy'])->name('documents.destroy');
        // ── Announcements + Evaluations + Presentations (Step 7) ──
        Route::get('/evaluations',   [EvaluationController::class, 'index'])->name('evaluations');
        Route::get('/presentations', [PresentationController::class, 'index'])->name('presentations');
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements');
        // ── Messages (Step 6) ──
        Route::get('/messages',      [MessageController::class, 'index'])->name('messages');
        Route::post('/messages',     [MessageController::class, 'store'])->name('messages.store');
        // ── Profile + Settings (Step 8) ──
        Route::get('/profile',            [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile',            [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password',   [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::get('/settings',           [ProfileController::class, 'settings'])->name('settings');
        Route::put('/settings',           [ProfileController::class, 'updateSettings'])->name('settings.update');
    });

// ── Supervisor Routes ──
Route::middleware(['auth', 'role:supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {
        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');

        // ── Baqi pages (placeholder - agle steps mein banain ge) ──
        // ── Proposal Reviews (Step 10) ──
        Route::get('/proposals',              [SupervisorProposalController::class, 'index'])->name('proposals');
        Route::put('/proposals/{id}/review',  [SupervisorProposalController::class, 'review'])->name('proposals.review');
        // ── My Teams + Team Detail (Step 11) ──
        Route::get('/teams',                      [SupervisorTeamController::class, 'index'])->name('teams');
        Route::get('/teams/{id}',                 [SupervisorTeamController::class, 'show'])->name('teams.show');
        Route::put('/milestones/{id}',            [SupervisorTeamController::class, 'updateMilestone'])->name('milestones.update');
        Route::get('/documents/{id}/download',    [SupervisorTeamController::class, 'downloadDocument'])->name('documents.download');
        // ── Evaluations + Messages (Step 12) ──
        Route::get('/evaluations',           [SupervisorEvaluationController::class, 'index'])->name('evaluations');
        Route::post('/evaluations',          [SupervisorEvaluationController::class, 'store'])->name('evaluations.store');
        Route::get('/messages',              [SupervisorMessageController::class, 'index'])->name('messages');
        Route::post('/messages/{userId}',    [SupervisorMessageController::class, 'store'])->name('messages.store');
    });

// ── Admin Routes ──
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // ── Baqi pages (placeholder - agle steps mein banain ge) ──
        // ── User Management + Assignments + Teams (Step 14) ──
        Route::get('/users',                     [AdminUserController::class, 'index'])->name('users');
        Route::post('/users/supervisor',         [AdminUserController::class, 'storeSupervisor'])->name('users.storeSupervisor');
        Route::delete('/users/{id}',             [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/assignments',               [AdminTeamController::class, 'assignments'])->name('assignments');
        Route::put('/assignments/{teamId}',      [AdminTeamController::class, 'assign'])->name('assignments.assign');
        Route::get('/teams',                     [AdminTeamController::class, 'teams'])->name('teams');
        Route::get('/announcements', fn() => 'Announcements — Step 15')->name('announcements');
        // ── Announcements + Presentations (Step 15) ──
        Route::get('/announcements',              [AdminAnnouncementController::class, 'index'])->name('announcements');
        Route::post('/announcements',             [AdminAnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/announcements/{id}',      [AdminAnnouncementController::class, 'destroy'])->name('announcements.destroy');
        Route::get('/presentations',              [AdminPresentationController::class, 'index'])->name('presentations');
        Route::post('/presentations',             [AdminPresentationController::class, 'store'])->name('presentations.store');
        Route::put('/presentations/{id}/status',  [AdminPresentationController::class, 'updateStatus'])->name('presentations.updateStatus');
    });