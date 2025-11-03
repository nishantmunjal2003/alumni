<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function users(Request $request)
    {
        $query = User::with('roles');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateUserRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $roles = $request->input('roles', []);
        $user->syncRoles($roles);

        return back()->with('success', 'User roles updated successfully!');
    }

    public function roles()
    {
        $roles = Role::withCount('users')->orderBy('name')->get();
        
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

        Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.roles')
            ->with('success', 'Role created successfully!');
    }

    public function destroyRole(Role $role)
    {
        // Prevent deletion of default roles
        if (in_array($role->name, ['admin', 'alumnus'])) {
            return back()->with('error', 'Cannot delete default roles.');
        }

        $role->delete();

        return back()->with('success', 'Role deleted successfully!');
    }
}
