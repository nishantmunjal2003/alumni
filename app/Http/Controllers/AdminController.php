<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Campaign;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'total_campaigns' => Campaign::count(),
            'pending_profiles' => User::where('profile_status', 'pending')
                ->where('profile_completed', true)
                ->count(),
            'recent_registrations' => User::latest()->limit(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::with('roles')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function updateUserRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
        ]);

        $user->syncRoles($validated['roles']);

        return back()->with('success', 'User roles updated successfully!');
    }

    public function roles()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function createRole()
    {
        return view('admin.roles.create');
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create(['name' => $validated['name'], 'guard_name' => 'web']);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully!');
    }

    public function destroyRole(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully!');
    }

    /**
     * Show pending profiles for approval.
     */
    public function pendingProfiles()
    {
        $pendingProfiles = User::where('profile_status', 'pending')
            ->where('profile_completed', true)
            ->latest()
            ->paginate(20);

        return view('admin.profiles.pending', compact('pendingProfiles'));
    }

    /**
     * Approve a user profile.
     */
    public function approveProfile(User $user)
    {
        if (!$user->profile_completed) {
            return back()->withErrors(['error' => 'Profile is not completed yet.']);
        }

        $user->update([
            'profile_status' => 'approved',
            'status' => 'active',
        ]);

        return back()->with('success', 'Profile approved successfully!');
    }

    /**
     * Block a user profile.
     */
    public function blockProfile(User $user)
    {
        $user->update([
            'profile_status' => 'blocked',
            'status' => 'inactive',
        ]);

        return back()->with('success', 'Profile blocked successfully!');
    }

    /**
     * View a specific profile for approval.
     */
    public function viewProfile(User $user)
    {
        return view('admin.profiles.view', compact('user'));
    }
}
