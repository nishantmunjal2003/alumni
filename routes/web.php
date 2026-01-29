<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AlumniMapController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Route;

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

    $totalAlumni = User::whereHas('roles', function ($q) {
        $q->where('name', 'alumni');
    })->count();

    $totalEvents = Event::where('status', 'published')->count();

    return view('welcome', compact('upcomingEvents', 'recentCampaigns', 'totalAlumni', 'totalEvents'));
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/verify-otp', [AuthController::class, 'showVerifyOtpForm'])->name('verify.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');
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
        
        // Employment & Image
        Route::get('/profile/employment', [ProfileController::class, 'editEmployment'])->name('profile.employment');
        Route::put('/profile/employment', [ProfileController::class, 'updateEmployment'])->name('profile.employment.update');
        Route::post('/profile/image', [ProfileController::class, 'updateImage'])->name('profile.image.update');
    });

    // Protected routes (require profile completion)
    Route::middleware(['profile.complete'])->group(function () {
        // Alumni routes
        Route::get('/dashboard', [AlumniController::class, 'dashboard'])->name('dashboard');

        // Routes requiring approval
        Route::middleware(['profile.approved'])->group(function () {
            Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
            Route::get('/alumni/map', [AlumniMapController::class, 'publicMap'])->name('alumni.map');
            Route::get('/alumni/map/locations', [AlumniMapController::class, 'getPublicAlumniLocations'])->name('alumni.map.locations');
            Route::get('/alumni/{id}', [AlumniController::class, 'show'])->name('alumni.show');
            Route::get('/alumni/{id}/edit', [AlumniController::class, 'edit'])->name('alumni.edit');
            Route::put('/alumni/{id}', [AlumniController::class, 'update'])->name('alumni.update');

            // Message routes
            Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
            Route::get('/messages/search', [MessageController::class, 'search'])->name('messages.search');
            Route::post('/messages/batch', [MessageController::class, 'batchStore'])->name('messages.batch.store');
            Route::get('/messages/unread/count', [MessageController::class, 'unreadCount'])->name('messages.unread.count');
            Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
            Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
        });

        // Event registration routes
        Route::get('/events/{event}/register', [EventRegistrationController::class, 'create'])->name('events.register');
        Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])->name('events.registrations.store');
        Route::get('/events/{event}/registrations/{registration}/edit', [EventRegistrationController::class, 'edit'])->name('events.registrations.edit');
        Route::put('/events/{event}/registrations/{registration}', [EventRegistrationController::class, 'update'])->name('events.registrations.update');
        Route::delete('/events/{event}/registrations/{registration}', [EventRegistrationController::class, 'destroy'])->name('events.registrations.destroy');
        Route::get('/events/{event}/fellows', [EventRegistrationController::class, 'fellows'])->name('events.fellows');
    });
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminController::class, 'editAlumni'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateAlumni'])->name('users.update');
    Route::post('/users/{user}/roles', [AdminController::class, 'updateUserRoles'])->name('users.roles.update');
    Route::post('/users/{user}/roles/{role}/toggle', [AdminController::class, 'toggleUserRole'])->name('users.roles.toggle');
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
    Route::get('/profiles/missing-details/email', [AdminController::class, 'showMissingDetailsEmailForm'])->name('profiles.missing-details.email');
    Route::post('/profiles/missing-details/email', [AdminController::class, 'sendMissingDetailsEmail'])->name('profiles.missing-details.email.send');

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
    Route::get('/alumni/map', [ManagerController::class, 'alumniMap'])->name('alumni.map');
    Route::get('/alumni/map/locations', [ManagerController::class, 'getAlumniLocations'])->name('alumni.map.locations');
    Route::get('/alumni/export/excel', [ManagerController::class, 'exportAlumni'])->name('alumni.export');
    Route::get('/alumni/email/bulk', [ManagerController::class, 'showBulkEmailForm'])->name('alumni.email.bulk');
    Route::post('/alumni/email/bulk', [ManagerController::class, 'sendBulkEmail'])->name('alumni.email.bulk.send');

    // Event management
    Route::get('/events', [ManagerController::class, 'eventsIndex'])->name('events.index');
    Route::get('/events/create', [ManagerController::class, 'eventsCreate'])->name('events.create');
    Route::post('/events', [ManagerController::class, 'eventsStore'])->name('events.store');
    Route::get('/events/{id}/edit', [ManagerController::class, 'eventsEdit'])->name('events.edit');
    Route::put('/events/{id}', [ManagerController::class, 'eventsUpdate'])->name('events.update');
    Route::delete('/events/{id}', [ManagerController::class, 'eventsDestroy'])->name('events.destroy');
    Route::get('/events/{id}/registrations', [ManagerController::class, 'eventsShowRegistrations'])->name('events.registrations');
    Route::get('/events/{id}/email', [ManagerController::class, 'eventsShowEmailForm'])->name('events.email');
    Route::post('/events/{id}/email', [ManagerController::class, 'eventsSendEmail'])->name('events.email.send');

    // Campaign management
    Route::get('/campaigns', [ManagerController::class, 'campaignsIndex'])->name('campaigns.index');
    Route::get('/campaigns/create', [ManagerController::class, 'campaignsCreate'])->name('campaigns.create');
    Route::post('/campaigns', [ManagerController::class, 'campaignsStore'])->name('campaigns.store');
    Route::get('/campaigns/{id}/edit', [ManagerController::class, 'campaignsEdit'])->name('campaigns.edit');
    Route::put('/campaigns/{id}', [ManagerController::class, 'campaignsUpdate'])->name('campaigns.update');
    Route::delete('/campaigns/{id}', [ManagerController::class, 'campaignsDestroy'])->name('campaigns.destroy');
    Route::get('/campaigns/{id}/email', [ManagerController::class, 'campaignsShowEmailForm'])->name('campaigns.email');
    Route::post('/campaigns/{id}/email', [ManagerController::class, 'campaignsSendEmail'])->name('campaigns.email.send');
});

// DataEntry routes
Route::middleware(['auth', 'dataentry'])->prefix('dataentry')->name('dataentry.')->group(function () {
    Route::get('/', [DataEntryController::class, 'dashboard'])->name('dashboard');
    Route::get('/profiles', [DataEntryController::class, 'index'])->name('profiles.index');
    Route::get('/profiles/{user}', [DataEntryController::class, 'show'])->name('profiles.show');
    Route::get('/profiles/{user}/edit', [DataEntryController::class, 'edit'])->name('profiles.edit');
    Route::put('/profiles/{user}', [DataEntryController::class, 'update'])->name('profiles.update');
    Route::post('/profiles/{user}/approve', [DataEntryController::class, 'approve'])->name('profiles.approve');
    Route::post('/profiles/{user}/block', [DataEntryController::class, 'block'])->name('profiles.block');
    Route::get('/profiles/{user}/email', [DataEntryController::class, 'showEmailForm'])->name('profiles.email');
    Route::post('/profiles/{user}/email', [DataEntryController::class, 'sendEmail'])->name('profiles.email.send');
});
