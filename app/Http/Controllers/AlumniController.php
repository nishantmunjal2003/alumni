<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('status', 'active');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('major', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('current_position', 'like', "%{$search}%")
                  ->orWhere('graduation_year', 'like', "%{$search}%");
            });
        }
        
        $alumni = $query->orderBy('name')->paginate(12)->withQueryString();
        
        // If AJAX request, return JSON with HTML
        if ($request->ajax()) {
            $html = view('alumni.partials.alumni-grid', compact('alumni'))->render();
            $pagination = view('alumni.partials.pagination', compact('alumni'))->render();
            
            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'count' => $alumni->total()
            ]);
        }
        
        return view('alumni.index', compact('alumni'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('alumni.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'graduation_year' => 'nullable|string|max:4',
            'major' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'current_position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'active';
        
        // Format name: First letter capital, rest lowercase
        if (isset($validated['name'])) {
            $validated['name'] = ucfirst(strtolower(trim($validated['name'])));
        }

        User::create($validated);

        return redirect()->route('alumni.index')
            ->with('success', 'Alumni registered successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $alumni)
    {
        return view('alumni.show', compact('alumni'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $alumni)
    {
        // Require authentication
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to edit your profile.');
        }
        
        // Only allow users to edit their own profile
        if (Auth::id() !== $alumni->id) {
            abort(403, 'You can only edit your own profile.');
        }
        
        return view('alumni.edit', compact('alumni'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $alumni)
    {
        // Only allow users to update their own profile
        if (Auth::id() !== $alumni->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $alumni->id,
            'phone' => 'nullable|string|max:20',
            'graduation_year' => 'nullable|string|max:4',
            'major' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'current_position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Format name: First letter capital, rest lowercase
        if (isset($validated['name'])) {
            $validated['name'] = ucfirst(strtolower(trim($validated['name'])));
        }
        
        if ($request->hasFile('profile_image')) {
            if ($alumni->profile_image) {
                Storage::disk('public')->delete($alumni->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        $alumni->update($validated);

        return redirect()->route('alumni.show', $alumni)
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $alumni)
    {
        // Only allow users to delete their own profile
        if (Auth::id() !== $alumni->id) {
            abort(403);
        }

        if ($alumni->profile_image) {
            Storage::disk('public')->delete($alumni->profile_image);
        }

        $alumni->delete();

        return redirect()->route('alumni.index')
            ->with('success', 'Profile deleted successfully!');
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        
        // Get alumni from the same batch (graduation year)
        $batchAlumni = collect([]);
        if ($user->graduation_year) {
            $batchAlumni = User::where('graduation_year', $user->graduation_year)
                ->where('status', 'active')
                ->where('id', '!=', $user->id)
                ->orderBy('name')
                ->get();
        }

        // Get all other alumni for search (excluding batch alumni)
        $query = User::where('status', 'active')
            ->where('id', '!=', $user->id);

        if ($user->graduation_year) {
            $query->where('graduation_year', '!=', $user->graduation_year);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('major', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('current_position', 'like', "%{$search}%")
                  ->orWhere('graduation_year', 'like', "%{$search}%");
            });
        }

        $allAlumni = $query->orderBy('name')->paginate(12)->withQueryString();

        // Get count of registered alumni (for events)
        $registeredCount = EventRegistration::distinct('user_id')->count('user_id');

        // Get total alumni count (excluding current user)
        $totalAlumniCount = User::where('status', 'active')->where('id', '!=', $user->id)->count();

        // Get upcoming events with registration status
        $upcomingEvents = Event::with('creator')
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(5)
            ->get()
            ->map(function($event) use ($user) {
                $event->is_registered = EventRegistration::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->exists();
                return $event;
            });

        return view('alumni.dashboard', compact('batchAlumni', 'allAlumni', 'registeredCount', 'user', 'totalAlumniCount', 'upcomingEvents'));
    }
}
