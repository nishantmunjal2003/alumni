<?php

namespace App\Http\Controllers;

use App\Mail\EventInvitationMail;
use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $query = Event::where('status', 'published')
            ->where('event_start_date', '>=', now())
            ->orderBy('event_start_date', 'asc');

        if (auth()->check()) {
            $query->with(['registrations' => function ($q) {
                $q->where('user_id', auth()->id());
            }]);
        }

        $events = $query->paginate(12);

        return view('events.index', compact('events'));
    }

    public function show($id)
    {
        $event = Event::with(['creator', 'registrations.user'])->findOrFail($id);
        $isRegistered = auth()->check() && $event->registrations()->where('user_id', auth()->id())->exists();
        $registration = null;

        if ($isRegistered) {
            $registration = $event->registrations()->where('user_id', auth()->id())->first();
        }

        return view('events.show', compact('event', 'isRegistered', 'registration'));
    }

    public function adminIndex()
    {
        $events = Event::with('creator')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'event_start_date' => 'required|date|after_or_equal:today',
            'event_end_date' => 'nullable|date|after:event_start_date',
            'google_maps_link' => 'required|url|max:500',
            'venue' => 'required|string|max:255',
            'status' => 'required|in:draft,published',
            'target_graduation_years' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $validated['created_by'] = auth()->id();
        $event = Event::create($validated);

        if ($request->has('target_graduation_years') && $request->status === 'published') {
            $this->sendInvitations($event);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);

        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'event_start_date' => 'required|date|after_or_equal:today',
            'event_end_date' => 'nullable|date|after:event_start_date',
            'google_maps_link' => 'required|url|max:500',
            'venue' => 'required|string|max:255',
            'status' => 'required|in:draft,published',
            'target_graduation_years' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        if ($request->has('target_graduation_years') && $request->status === 'published' && ! $event->invites_sent) {
            $this->sendInvitations($event);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully!');
    }

    public function resendInvites($id)
    {
        $event = Event::findOrFail($id);
        $this->sendInvitations($event);

        return back()->with('success', 'Invitations resent successfully!');
    }

    private function sendInvitations(Event $event)
    {
        if ($event->invites_sent) {
            return;
        }

        $targetYears = $event->target_graduation_years ?? [];

        if (empty($targetYears)) {
            return;
        }

        $users = User::whereIn('graduation_year', $targetYears)
            ->where('status', 'active')
            ->get();

        foreach ($users as $user) {
            $invitation = EventInvitation::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            Mail::to($user->email)->send(new EventInvitationMail($event, $user));
        }

        $event->update(['invites_sent' => true]);
    }
}
