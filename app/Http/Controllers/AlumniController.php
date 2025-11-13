<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumniController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $batchmates = User::where('graduation_year', $user->graduation_year)
            ->where('id', '!=', $user->id)
            ->where('status', 'active')
            ->limit(10)
            ->get();

        $upcomingEvents = \App\Models\Event::where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(5)
            ->get();

        return view('alumni.dashboard', compact('batchmates', 'upcomingEvents'));
    }

    public function index(Request $request)
    {
        $query = User::where('status', 'active');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('major', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('current_position', 'like', "%{$search}%")
                  ->orWhere('graduation_year', 'like', "%{$search}%");
            });
        }

        if ($request->has('graduation_year')) {
            $query->where('graduation_year', $request->graduation_year);
        }

        $alumni = $query->paginate(12);

        if ($request->ajax()) {
            return view('alumni.partials.alumni-list', compact('alumni'))->render();
        }

        $graduationYears = User::whereNotNull('graduation_year')
            ->distinct()
            ->orderBy('graduation_year', 'desc')
            ->pluck('graduation_year');

        return view('alumni.index', compact('alumni', 'graduationYears'));
    }

    public function show($id)
    {
        $alumni = User::findOrFail($id);
        return view('alumni.show', compact('alumni'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id !== auth()->id()) {
            abort(403);
        }

        return view('alumni.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'graduation_year' => 'nullable|string|max:10',
            'major' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'current_position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        $user->update($validated);

        return redirect()->route('alumni.show', $user->id)->with('success', 'Profile updated successfully!');
    }
}
