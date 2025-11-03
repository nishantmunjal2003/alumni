<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Google OAuth routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

// Alumni routes - require authentication
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AlumniController::class, 'dashboard'])->name('dashboard');
    Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/{alumni}', [AlumniController::class, 'show'])->name('alumni.show')->where('alumni', '[0-9]+');
    Route::get('/alumni/create', [AlumniController::class, 'create'])->name('alumni.create');
    Route::post('/alumni', [AlumniController::class, 'store'])->name('alumni.store');
    Route::get('/alumni/{alumni}/edit', [AlumniController::class, 'edit'])->name('alumni.edit')->where('alumni', '[0-9]+');
    Route::put('/alumni/{alumni}', [AlumniController::class, 'update'])->name('alumni.update')->where('alumni', '[0-9]+');
    Route::patch('/alumni/{alumni}', [AlumniController::class, 'update'])->where('alumni', '[0-9]+');
    Route::delete('/alumni/{alumni}', [AlumniController::class, 'destroy'])->name('alumni.destroy')->where('alumni', '[0-9]+');
});

// Campaign routes - public viewing
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');

// Event routes - public viewing
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Event Registration routes - require authentication
Route::middleware('auth')->group(function () {
    Route::get('/events/{event}/register', [EventRegistrationController::class, 'create'])->name('events.register');
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])->name('events.register.store');
    Route::get('/events/{event}/registrations/{registration}/edit', [EventRegistrationController::class, 'edit'])->name('events.registrations.edit');
    Route::put('/events/{event}/registrations/{registration}', [EventRegistrationController::class, 'update'])->name('events.registrations.update');
    Route::delete('/events/{event}/registrations/{registration}', [EventRegistrationController::class, 'destroy'])->name('events.registrations.destroy');
    Route::get('/events/{event}/fellows', [EventRegistrationController::class, 'registeredFellows'])->name('events.fellows');
    
    // Messages routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/unread/count', [MessageController::class, 'unreadCount'])->name('messages.unread.count');
});

// Admin routes - require admin role
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/roles', [AdminController::class, 'updateUserRoles'])->name('users.updateRoles');
    
    // Role management
    Route::get('/roles', [AdminController::class, 'roles'])->name('roles');
    Route::get('/roles/create', [AdminController::class, 'createRole'])->name('roles.create');
    Route::post('/roles', [AdminController::class, 'storeRole'])->name('roles.store');
    Route::delete('/roles/{role}', [AdminController::class, 'destroyRole'])->name('roles.destroy');
    
    // Campaign management
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    
    // Event management
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/{event}/resend-invites', [EventController::class, 'resendInvites'])->name('events.resend-invites');
});
