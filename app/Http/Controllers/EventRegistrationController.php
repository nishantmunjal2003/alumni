<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventRegistrationPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingRegistration) {
            return redirect()->route('events.registrations.edit', [$event->id, $existingRegistration->id]);
        }

        return view('events.register', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingRegistration) {
            return redirect()->route('events.registrations.edit', [$event->id, $existingRegistration->id])
                ->with('error', 'You have already registered for this event.');
        }

        $validated = $request->validate([
            'arrival_date' => 'nullable|date',
            'coming_from_city' => 'nullable|string|max:255',
            'arrival_time' => 'nullable|date_format:H:i',
            'needs_stay' => 'boolean',
            'coming_with_family' => 'boolean',
            'travel_mode' => 'nullable|in:car,train,flight,bus,other',
            'return_journey_details' => 'nullable|string',
            'memories_description' => 'nullable|string',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'image|max:2048',
            'friend_ids' => 'nullable|array',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['event_id'] = $event->id;
        $validated['needs_stay'] = $request->has('needs_stay');
        $validated['coming_with_family'] = $request->has('coming_with_family');

        $registration = EventRegistration::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('event-photos', 'public');
                EventRegistrationPhoto::create([
                    'event_registration_id' => $registration->id,
                    'photo_path' => $photoPath,
                ]);
            }
        }

        if ($request->has('friend_ids')) {
            $registration->friends()->attach($request->friend_ids);
        }

        return redirect()->route('events.show', $event->id)->with('success', 'Registration successful!');
    }

    public function edit(Event $event, EventRegistration $registration)
    {
        if ($registration->user_id !== auth()->id() || $registration->event_id !== $event->id) {
            abort(403);
        }

        return view('events.edit-registration', compact('event', 'registration'));
    }

    public function update(Request $request, Event $event, EventRegistration $registration)
    {
        if ($registration->user_id !== auth()->id() || $registration->event_id !== $event->id) {
            abort(403);
        }

        $validated = $request->validate([
            'arrival_date' => 'nullable|date',
            'coming_from_city' => 'nullable|string|max:255',
            'arrival_time' => 'nullable|date_format:H:i',
            'needs_stay' => 'boolean',
            'coming_with_family' => 'boolean',
            'travel_mode' => 'nullable|in:car,train,flight,bus,other',
            'return_journey_details' => 'nullable|string',
            'memories_description' => 'nullable|string',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'image|max:2048',
            'friend_ids' => 'nullable|array',
        ]);

        $validated['needs_stay'] = $request->has('needs_stay');
        $validated['coming_with_family'] = $request->has('coming_with_family');

        $registration->update($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('event-photos', 'public');
                EventRegistrationPhoto::create([
                    'event_registration_id' => $registration->id,
                    'photo_path' => $photoPath,
                ]);
            }
        }

        if ($request->has('friend_ids')) {
            $registration->friends()->sync($request->friend_ids);
        }

        return redirect()->route('events.show', $event->id)->with('success', 'Registration updated successfully!');
    }

    public function destroy(Event $event, EventRegistration $registration)
    {
        if ($registration->user_id !== auth()->id() || $registration->event_id !== $event->id) {
            abort(403);
        }

        foreach ($registration->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        $registration->delete();

        return redirect()->route('events.show', $event->id)->with('success', 'Registration cancelled successfully!');
    }

    public function fellows(Request $request, Event $event)
    {
        $query = EventRegistration::where('event_id', $event->id)
            ->with(['user', 'photos']);

        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('major', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('current_position', 'like', "%{$search}%")
                        ->orWhere('passing_year', 'like', "%{$search}%");
                })->orWhere('coming_from_city', 'like', "%{$search}%");
            });
        }

        $registrations = $query->get();

        // Get current user's passing year
        $currentUserPassingYear = auth()->user()->passing_year;

        // Group registrations by passing year
        $groupedRegistrations = $registrations->groupBy(function ($registration) {
            return $registration->user->passing_year ?? 'Other';
        });

        // Get sorted keys: current user's passing year first, then others by year descending
        $keys = $groupedRegistrations->keys()->toArray();

        usort($keys, function ($a, $b) {
            // Handle 'Other' group - always put it last
            if ($a === 'Other') {
                return 1;
            }
            if ($b === 'Other') {
                return -1;
            }

            // Sort numeric years descending
            return (int) $b <=> (int) $a;
        });

        // Move current user's passing year to the top if it exists
        if ($currentUserPassingYear && in_array($currentUserPassingYear, $keys)) {
            $keys = array_values(array_diff($keys, [$currentUserPassingYear]));
            array_unshift($keys, $currentUserPassingYear);
        }

        // Build sorted groups array while preserving Eloquent models
        $sortedGroups = [];
        foreach ($keys as $key) {
            $sortedGroups[$key] = $groupedRegistrations[$key];
        }

        return view('events.fellows', compact('event', 'registrations', 'sortedGroups', 'currentUserPassingYear'));
    }
}
