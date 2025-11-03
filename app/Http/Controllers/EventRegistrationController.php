<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventRegistrationPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventRegistrationController extends Controller
{
    public function create(Event $event)
    {
        // Check if user already registered
        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingRegistration) {
            return redirect()->route('events.show', $event)
                ->with('info', 'You have already registered for this event.');
        }

        // Get all active alumni for friend selection
        $alumni = User::where('status', 'active')
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();

        return view('event-registrations.create', compact('event', 'alumni'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'arrival_date' => 'nullable|date',
            'coming_from_city' => 'nullable|string|max:255',
            'arrival_time' => 'nullable|date_format:H:i',
            'needs_stay' => 'boolean',
            'coming_with_family' => 'boolean',
            'travel_mode' => 'nullable|in:car,train,flight,bus,other',
            'return_journey_details' => 'nullable|string',
            'memories_description' => 'nullable|string',
            'friends' => 'nullable|array',
            'friends.*' => 'exists:users,id',
            'memories_photos' => 'nullable|array',
            'memories_photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'photo_captions' => 'nullable|array',
            'photo_captions.*' => 'nullable|string|max:255',
        ]);

        // Check if already registered
        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingRegistration) {
            return redirect()->route('events.show', $event)
                ->with('error', 'You have already registered for this event.');
        }

        $validated['user_id'] = Auth::id();
        $validated['event_id'] = $event->id;
        $validated['needs_stay'] = $request->has('needs_stay');
        $validated['coming_with_family'] = $request->has('coming_with_family');

        $registration = EventRegistration::create($validated);

        // Attach friends
        if ($request->has('friends') && is_array($request->friends)) {
            $registration->friends()->sync($request->friends);
        }

        // Handle memory photos
        if ($request->hasFile('memories_photos')) {
            foreach ($request->file('memories_photos') as $index => $photo) {
                $photoPath = $photo->store('event-registrations/photos', 'public');
                
                $caption = $request->photo_captions[$index] ?? null;
                
                EventRegistrationPhoto::create([
                    'event_registration_id' => $registration->id,
                    'photo_path' => $photoPath,
                    'caption' => $caption,
                ]);
            }
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'You have successfully registered for this event!');
    }

    public function edit(Event $event, EventRegistration $registration)
    {
        if ($registration->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $registration->load('friends', 'photos');
        $alumni = User::where('status', 'active')
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();

        return view('event-registrations.edit', compact('event', 'registration', 'alumni'));
    }

    public function update(Request $request, Event $event, EventRegistration $registration)
    {
        if ($registration->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
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
            'friends' => 'nullable|array',
            'friends.*' => 'exists:users,id',
            'memories_photos' => 'nullable|array',
            'memories_photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'photo_captions' => 'nullable|array',
            'photo_captions.*' => 'nullable|string|max:255',
        ]);

        $validated['needs_stay'] = $request->has('needs_stay');
        $validated['coming_with_family'] = $request->has('coming_with_family');

        $registration->update($validated);

        // Update friends
        if ($request->has('friends') && is_array($request->friends)) {
            $registration->friends()->sync($request->friends);
        } else {
            $registration->friends()->detach();
        }

        // Handle new memory photos
        if ($request->hasFile('memories_photos')) {
            foreach ($request->file('memories_photos') as $index => $photo) {
                $photoPath = $photo->store('event-registrations/photos', 'public');
                
                $caption = $request->photo_captions[$index] ?? null;
                
                EventRegistrationPhoto::create([
                    'event_registration_id' => $registration->id,
                    'photo_path' => $photoPath,
                    'caption' => $caption,
                ]);
            }
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Your registration has been updated successfully!');
    }

    public function registeredFellows(Event $event)
    {
        // Get all registrations for this event from the same batch as the logged-in user
        $user = Auth::user();
        
        if (!$user->graduation_year) {
            return view('event-registrations.fellows', [
                'event' => $event,
                'fellows' => collect([]),
                'message' => 'Your graduation year is not set. Please update your profile.'
            ]);
        }

        $fellows = EventRegistration::where('event_id', $event->id)
            ->whereHas('user', function($query) use ($user) {
                $query->where('graduation_year', $user->graduation_year)
                      ->where('id', '!=', $user->id);
            })
            ->with(['user', 'friends'])
            ->get();

        return view('event-registrations.fellows', compact('event', 'fellows'));
    }

    public function destroy(Event $event, EventRegistration $registration)
    {
        if ($registration->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete photos
        foreach ($registration->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }

        // Detach friends
        $registration->friends()->detach();

        $registration->delete();

        return redirect()->route('events.show', $event)
            ->with('success', 'Registration cancelled successfully.');
    }
}
