<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AlumniMapController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

// Public routes
Route::get('/', function () {
    $upcomingEvents = Event::where('status', 'published')
        ->where('event_start_date', '>=', now())
        ->orderBy('event_start_date', 'asc')
        ->limit(3)
        ->get();

    $recentCampaigns = Campaign::where('status', 'published')
        ->where('end_date', '>=', now())
        ->orderBy('start_date', 'desc')
        ->limit(3)
        ->get();

    // Get admin and manager user IDs to exclude from alumni count
    $adminRoleId = Role::where('name', 'admin')->value('id');
    $managerRoleId = Role::where('name', 'manager')->value('id');
    
    $excludedIds = [];
    if ($adminRoleId) {
        $adminIds = DB::table('model_has_roles')
            ->where('role_id', $adminRoleId)
            ->where('model_type', User::class)
            ->pluck('model_id')
            ->toArray();
        $excludedIds = array_merge($excludedIds, $adminIds);
    }
    
    if ($managerRoleId) {
        $managerIds = DB::table('model_has_roles')
            ->where('role_id', $managerRoleId)
            ->where('model_type', User::class)
            ->pluck('model_id')
            ->toArray();
        $excludedIds = array_merge($excludedIds, $managerIds);
    }
    
    // Count all registered alumni (active or inactive), excluding admins and managers
    $totalAlumni = User::when(!empty($excludedIds), function ($query) use ($excludedIds) {
            return $query->whereNotIn('id', array_unique($excludedIds));
        })
        ->count();

    $totalEvents = Event::where('status', 'published')->count();

    return view('welcome', compact('upcomingEvents', 'recentCampaigns', 'totalAlumni', 'totalEvents'));
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Public event and campaign routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{id}', [CampaignController::class, 'show'])->name('campaigns.show');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Profile completion routes (excluded from profile.complete middleware)
    Route::get('/profile/complete', [ProfileController::class, 'complete'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'store'])->name('profile.store');

    // Profile edit routes (accessible after profile completion)
    Route::middleware(['profile.complete'])->group(function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    });

    // Protected routes (require profile completion and approval)
    Route::middleware(['profile.complete'])->group(function () {
        // Alumni routes
        Route::get('/dashboard', [AlumniController::class, 'dashboard'])->name('dashboard');
        Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
        Route::get('/alumni/map', [AlumniMapController::class, 'publicMap'])->name('alumni.map');
        Route::get('/alumni/map/locations', [AlumniMapController::class, 'getPublicAlumniLocations'])->name('alumni.map.locations');
        Route::get('/alumni/{id}', [AlumniController::class, 'show'])->name('alumni.show');
        Route::get('/alumni/{id}/edit', [AlumniController::class, 'edit'])->name('alumni.edit');
        Route::put('/alumni/{id}', [AlumniController::class, 'update'])->name('alumni.update');

        // Event registration routes
        Route::get('/events/{event}/register', [EventRegistrationController::class, 'create'])->name('events.register');
        Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])->name('events.registrations.store');
        Route::get('/events/{event}/registrations/{registration}/edit', [EventRegistrationController::class, 'edit'])->name('events.registrations.edit');
        Route::put('/events/{event}/registrations/{registration}', [EventRegistrationController::class, 'update'])->name('events.registrations.update');
        Route::delete('/events/{event}/registrations/{registration}', [EventRegistrationController::class, 'destroy'])->name('events.registrations.destroy');
        Route::get('/events/{event}/fellows', [EventRegistrationController::class, 'fellows'])->name('events.fellows');

        // Message routes
        Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
        Route::get('/messages/unread/count', [MessageController::class, 'unreadCount'])->name('messages.unread.count');
    });
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminController::class, 'editAlumni'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateAlumni'])->name('users.update');
    Route::post('/users/{user}/roles', [AdminController::class, 'updateUserRoles'])->name('users.roles.update');
    Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');

    // Role management
    Route::get('/roles', [AdminController::class, 'roles'])->name('roles.index');
    Route::get('/roles/create', [AdminController::class, 'createRole'])->name('roles.create');
    Route::post('/roles', [AdminController::class, 'storeRole'])->name('roles.store');
    Route::delete('/roles/{role}', [AdminController::class, 'destroyRole'])->name('roles.destroy');

    // Event management
    Route::get('/events', [EventController::class, 'adminIndex'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/{id}/resend-invites', [EventController::class, 'resendInvites'])->name('events.resend-invites');
    Route::get('/events/{id}/registrations', [EventController::class, 'showRegistrations'])->name('events.registrations');
    Route::get('/events/{id}/email', [EventController::class, 'showEmailForm'])->name('events.email');
    Route::post('/events/{id}/email', [EventController::class, 'sendEmail'])->name('events.email.send');
    Route::get('/events/{id}/export', [EventController::class, 'exportRegistrations'])->name('events.export');

    // Campaign management
    Route::get('/campaigns', [CampaignController::class, 'adminIndex'])->name('campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{id}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{id}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{id}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::get('/campaigns/{id}/email', [CampaignController::class, 'showBulkEmailForm'])->name('campaigns.email');
    Route::post('/campaigns/{id}/email', [CampaignController::class, 'sendBulkEmail'])->name('campaigns.email.send');

    // Profile approval management
    Route::get('/profiles/pending', [AdminController::class, 'pendingProfiles'])->name('profiles.pending');
    Route::get('/profiles/{user}', [AdminController::class, 'viewProfile'])->name('profiles.view');
    Route::post('/profiles/{user}/approve', [AdminController::class, 'approveProfile'])->name('profiles.approve');
    Route::post('/profiles/{user}/block', [AdminController::class, 'blockProfile'])->name('profiles.block');

    // Alumni directory management
    Route::get('/alumni', [AdminController::class, 'alumniDirectory'])->name('alumni.index');
    Route::get('/alumni/map', [AlumniMapController::class, 'adminMap'])->name('alumni.map');
    Route::get('/alumni/map/locations', [AlumniMapController::class, 'getAlumniLocations'])->name('alumni.map.locations');
    Route::get('/alumni/export/excel', [AdminController::class, 'exportAlumni'])->name('alumni.export');
    Route::get('/alumni/{user}', [AdminController::class, 'viewAlumni'])->name('alumni.view');

    // Email functionality
    Route::get('/alumni/{user}/email', [AdminController::class, 'showEmailForm'])->name('alumni.email');
    Route::post('/alumni/{user}/email', [AdminController::class, 'sendEmailToAlumni'])->name('alumni.email.send');
    Route::get('/alumni/email/bulk', [AdminController::class, 'showBulkEmailForm'])->name('alumni.email.bulk');
    Route::post('/alumni/email/bulk', [AdminController::class, 'sendBulkEmail'])->name('alumni.email.bulk.send');
});

// Manager routes
Route::middleware(['auth', 'manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/', [ManagerController::class, 'dashboard'])->name('dashboard');
    Route::get('/alumni', [ManagerController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/{user}', [ManagerController::class, 'show'])->name('alumni.show');
    Route::post('/alumni/{user}/activate', [ManagerController::class, 'activate'])->name('alumni.activate');
    Route::post('/alumni/{user}/deactivate', [ManagerController::class, 'deactivate'])->name('alumni.deactivate');
});
