<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'manager']);
    }

    /**
     * Manager dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'pending_profiles' => User::where('profile_status', 'pending')
                ->where('profile_completed', true)
                ->count(),
            'recent_registrations' => User::latest()->limit(10)->get(),
        ];

        return view('manager.dashboard', compact('stats'));
    }

    /**
     * List all alumni/users.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('course', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by profile status
        if ($request->has('profile_status') && $request->profile_status) {
            $query->where('profile_status', $request->profile_status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('manager.alumni.index', compact('users'));
    }

    /**
     * View alumni details.
     */
    public function show(User $user)
    {
        return view('manager.alumni.show', compact('user'));
    }

    /**
     * Activate a user account.
     */
    public function activate(User $user)
    {
        $user->update([
            'status' => 'active',
        ]);

        return back()->with('success', 'Account activated successfully!');
    }

    /**
     * Deactivate a user account.
     */
    public function deactivate(User $user)
    {
        $user->update([
            'status' => 'inactive',
            'profile_status' => 'blocked',
        ]);

        return back()->with('success', 'Account deactivated successfully!');
    }
}
