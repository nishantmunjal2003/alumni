<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\User;
use App\Mail\EventInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('creator')
            ->published()
            ->orderBy('event_date', 'asc')
            ->paginate(12);
        
        return view('events.index', compact('events'));
    }

    public function create()
    {
        // Get unique graduation years from users
        $graduationYears = User::whereNotNull('graduation_year')
            ->where('status', 'active')
            ->distinct()
            ->orderBy('graduation_year', 'desc')
            ->pluck('graduation_year')
            ->toArray();
        
        return view('events.create', compact('graduationYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,cancelled,completed',
            'target_graduation_years' => 'nullable|array',
            'target_graduation_years.*' => 'string',
        ]);

        $validated['created_by'] = Auth::id();
        
        // Store target graduation years as JSON
        if ($request->has('target_graduation_years')) {
            $validated['target_graduation_years'] = $request->input('target_graduation_years');
        } else {
            $validated['target_graduation_years'] = null;
        }
        
        $validated['invites_sent'] = false;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($validated);

        // Automatically send invites to all selected batches when event is created
        if (!empty($validated['target_graduation_years'])) {
            $sentCount = $this->sendEventInvites($event, $validated['target_graduation_years']);
            $event->update(['invites_sent' => true]);
            
            $mailDriver = config('mail.default');
            $message = "Event created successfully! ";
            
            if ($mailDriver === 'log') {
                $message .= "Invitation emails are being logged (not sent). Check storage/logs/laravel.log for details. ";
                $message .= "Configure SMTP in .env file to actually send emails. {$sentCount} invitations processed.";
            } else {
                $message .= "Invitations have been sent to {$sentCount} alumni.";
            }
            
            return redirect()->route('events.index')
                ->with('success', $message)
                ->with('info', $mailDriver === 'log' ? 'Mail driver is set to "log". Emails are saved to log files only.' : null);
        }

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        if ($event->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Get unique graduation years from users
        $graduationYears = User::whereNotNull('graduation_year')
            ->where('status', 'active')
            ->distinct()
            ->orderBy('graduation_year', 'desc')
            ->pluck('graduation_year')
            ->toArray();

        return view('events.edit', compact('event', 'graduationYears'));
    }

    public function update(Request $request, Event $event)
    {
        if ($event->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,cancelled,completed',
            'target_graduation_years' => 'nullable|array',
            'target_graduation_years.*' => 'string',
        ]);

        // Store target graduation years as JSON
        if ($request->has('target_graduation_years')) {
            $validated['target_graduation_years'] = $request->input('target_graduation_years');
        } else {
            $validated['target_graduation_years'] = null;
        }

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        // Send invites if batches are updated and invites haven't been sent yet
        if (!$event->invites_sent && !empty($validated['target_graduation_years'])) {
            $sentCount = $this->sendEventInvites($event, $validated['target_graduation_years']);
            $event->update(['invites_sent' => true]);
            
            $mailDriver = config('mail.default');
            $message = "Event updated successfully! ";
            
            if ($mailDriver === 'log') {
                $message .= "Invitation emails are being logged (not sent). Check storage/logs/laravel.log for details. ";
                $message .= "Configure SMTP in .env file to actually send emails. {$sentCount} invitations processed.";
            } else {
                $message .= "Invitations have been sent to {$sentCount} alumni.";
            }
            
            return redirect()->route('events.show', $event)
                ->with('success', $message)
                ->with('info', $mailDriver === 'log' ? 'Mail driver is set to "log". Emails are saved to log files only.' : null);
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        if ($event->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }

    public function resendInvites(Event $event)
    {
        if ($event->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if (empty($event->target_graduation_years)) {
            return redirect()->route('events.show', $event)
                ->with('error', 'No graduation years are set for this event to send invitations.');
        }

        $sentCount = $this->sendEventInvites($event, $event->target_graduation_years);
        
        $mailDriver = config('mail.default');
        $message = $mailDriver === 'log' 
            ? "Invitations logged to {$sentCount} alumni (check storage/logs/laravel.log). Configure SMTP to actually send emails."
            : "Invitations have been resent to {$sentCount} alumni.";

        return redirect()->route('events.show', $event)
            ->with('success', $message);
    }

    /**
     * Send event invitations to users in specified graduation years
     */
    private function sendEventInvites(Event $event, array $graduationYears)
    {
        // Get users from specified graduation years
        $users = User::whereIn('graduation_year', $graduationYears)
            ->where('status', 'active')
            ->whereNotNull('email')
            ->get();

        \Log::info('Sending event invitations', [
            'event_id' => $event->id,
            'event_title' => $event->title,
            'graduation_years' => $graduationYears,
            'users_count' => $users->count(),
            'mail_driver' => config('mail.default')
        ]);

        $sentCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            // Check if invitation already exists
            $invitation = EventInvitation::firstOrCreate([
                'event_id' => $event->id,
                'user_id' => $user->id,
            ], [
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            // Send email if newly created
            if ($invitation->wasRecentlyCreated) {
                try {
                    Mail::to($user->email)->send(new EventInvitationMail($event, $user));
                    $sentCount++;
                    \Log::info('Event invitation sent successfully', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'event_id' => $event->id
                    ]);
                } catch (\Exception $e) {
                    $failedCount++;
                    \Log::error('Failed to send event invitation', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'event_id' => $event->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            } else {
                \Log::info('Event invitation already exists, skipping', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'event_id' => $event->id
                ]);
            }
        }

        \Log::info('Event invitation process completed', [
            'event_id' => $event->id,
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
            'total_users' => $users->count()
        ]);

        return $sentCount;
    }
}
