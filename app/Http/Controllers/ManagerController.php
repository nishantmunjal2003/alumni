<?php

namespace App\Http\Controllers;

use App\Mail\AlumniEmail;
use App\Mail\EventInvitationMail;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
        // Use a single query with selectRaw to get multiple counts efficiently
        $userStats = User::selectRaw('
            COUNT(*) as total_users,
            SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active_users,
            SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive_users,
            SUM(CASE WHEN profile_status = "pending" AND profile_completed = 1 THEN 1 ELSE 0 END) as pending_profiles
        ')->first();

        $stats = [
            'total_users' => (int) $userStats->total_users,
            'active_users' => (int) $userStats->active_users,
            'inactive_users' => (int) $userStats->inactive_users,
            'total_events' => Event::count(),
            'total_campaigns' => Campaign::count(),
            'pending_profiles' => (int) $userStats->pending_profiles,
            'recent_registrations' => User::latest()->limit(10)->get(),
        ];

        return view('manager.dashboard', compact('stats'));
    }

    /**
     * List all alumni/users (alumni directory).
     */
    public function index(Request $request)
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'manager']);
        });

        // Search functionality
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('enrollment_no', 'like', "%{$search}%")
                        ->orWhere('major', 'like', "%{$search}%")
                        ->orWhere('course', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('current_position', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('passing_year', 'like', "%{$search}%");
                });
            }
        }

        // Filter by passing year
        if ($request->filled('passing_year')) {
            $query->where('passing_year', $request->passing_year);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by profile status
        if ($request->filled('profile_status')) {
            $query->where('profile_status', $request->profile_status);
        }

        // Sorting functionality
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        // Validate sort_by and sort_order
        $allowedSortFields = ['name', 'passing_year', 'course', 'company'];
        if (! in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'name';
        }
        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);

        $alumni = $query->paginate(20)->appends($request->except('page'));

        // Get total count for display
        $totalAlumniCount = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'manager']);
        })->count();

        // Get passing years for filter
        $passingYears = User::whereNotNull('passing_year')
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['admin', 'manager']);
            })
            ->distinct()
            ->orderBy('passing_year', 'desc')
            ->pluck('passing_year');

        if ($request->ajax()) {
            return view('manager.alumni.partials.alumni-list', compact('alumni', 'sortBy', 'sortOrder'))->render();
        }

        return view('manager.alumni.index', compact('alumni', 'passingYears', 'totalAlumniCount', 'sortBy', 'sortOrder'));
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

    // ==================== Events Management ====================

    /**
     * List all events (manager can see all but only delete own).
     */
    public function eventsIndex()
    {
        $events = Event::with('creator')
            ->withCount('registrations')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('manager.events.index', compact('events'));
    }

    /**
     * Show create event form.
     */
    public function eventsCreate()
    {
        return view('manager.events.create');
    }

    /**
     * Store a new event.
     */
    public function eventsStore(Request $request)
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
            $this->sendEventInvitations($event);
        }

        return redirect()->route('manager.events.index')->with('success', 'Event created successfully!');
    }

    /**
     * Show edit event form (only own events).
     */
    public function eventsEdit($id)
    {
        $event = Event::findOrFail($id);

        // Only allow editing own events
        if ($event->created_by !== auth()->id()) {
            return redirect()->route('manager.events.index')
                ->with('error', 'You can only edit your own events.');
        }

        return view('manager.events.edit', compact('event'));
    }

    /**
     * Update an event (only own events).
     */
    public function eventsUpdate(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Only allow updating own events
        if ($event->created_by !== auth()->id()) {
            return redirect()->route('manager.events.index')
                ->with('error', 'You can only update your own events.');
        }

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
            $this->sendEventInvitations($event);
        }

        return redirect()->route('manager.events.index')->with('success', 'Event updated successfully!');
    }

    /**
     * Show event registrations (can view all events' registrations).
     */
    public function eventsShowRegistrations($id)
    {
        $event = Event::findOrFail($id);
        $registrations = $event->registrations()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('manager.events.registrations', compact('event', 'registrations'));
    }

    /**
     * Delete an event (only own events).
     */
    public function eventsDestroy($id)
    {
        $event = Event::findOrFail($id);

        // Only allow deletion of own events
        if ($event->created_by !== auth()->id()) {
            return redirect()->route('manager.events.index')
                ->with('error', 'You can only delete your own events.');
        }

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('manager.events.index')->with('success', 'Event deleted successfully!');
    }

    /**
     * Show email form for sending emails to filtered alumni about event.
     */
    public function eventsShowEmailForm(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Start with base query (excluding admins and managers)
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'manager']);
        });

        // Apply filters
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('enrollment_no', 'like', "%{$search}%")
                        ->orWhere('major', 'like', "%{$search}%")
                        ->orWhere('course', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('current_position', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('passing_year', 'like', "%{$search}%");
                });
            }
        }

        // Filter by passing year
        if ($request->filled('passing_year')) {
            $query->where('passing_year', $request->passing_year);
        }

        // Filter by status - default to active
        if ($request->filled('status') && $request->status === 'inactive') {
            $query->where('status', 'inactive');
        } else {
            $query->where('status', 'active');
        }

        // Filter by profile status
        if ($request->filled('profile_status')) {
            $query->where('profile_status', $request->profile_status);
        }

        $query->orderBy('name', 'asc');
        $recipients = $query->get();
        $recipientCount = $recipients->count();

        // Get passing years for filter dropdown
        $passingYears = User::whereNotNull('passing_year')
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['admin', 'manager']);
            })
            ->where('status', 'active')
            ->distinct()
            ->orderBy('passing_year', 'desc')
            ->pluck('passing_year');

        $statusFilter = $request->get('status', 'active');
        $profileStatusFilter = $request->get('profile_status', '');

        return view('manager.events.email-form', compact('event', 'recipients', 'recipientCount', 'passingYears', 'statusFilter', 'profileStatusFilter'));
    }

    /**
     * Send email to filtered alumni about event.
     */
    public function eventsSendEmail(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_ids' => 'required|array',
            'recipient_ids.*' => 'exists:users,id',
        ]);

        try {
            // Get recipients
            $recipients = User::whereIn('id', $validated['recipient_ids'])
                ->whereDoesntHave('roles', function ($q) {
                    $q->whereIn('name', ['admin', 'manager']);
                })
                ->where('status', 'active')
                ->get();

            // Queue all emails instead of sending synchronously
            $queuedCount = 0;
            foreach ($recipients as $recipient) {
                try {
                    Mail::to($recipient->email)->queue(new AlumniEmail($recipient, $validated['subject'], $validated['message']));
                    $queuedCount++;
                } catch (\Exception $e) {
                    \Log::error("Failed to queue event email to {$recipient->email}: ".$e->getMessage());
                }
            }

            return redirect()->route('manager.events.index')
                ->with('success', "{$queuedCount} emails have been queued for sending. Emails will be sent in the background.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to send emails: '.$e->getMessage()]);
        }
    }

    /**
     * Send event invitations to filtered alumni.
     */
    private function sendEventInvitations(Event $event): void
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
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['admin', 'manager']);
            })
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

    // ==================== Campaigns Management ====================

    /**
     * List all campaigns (manager can see all but only delete own).
     */
    public function campaignsIndex()
    {
        $campaigns = Campaign::with('creator')->orderBy('created_at', 'desc')->paginate(20);

        return view('manager.campaigns.index', compact('campaigns'));
    }

    /**
     * Show create campaign form.
     */
    public function campaignsCreate()
    {
        return view('manager.campaigns.create');
    }

    /**
     * Store a new campaign.
     */
    public function campaignsStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'donation_link' => 'nullable|url|max:500',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $validated['created_by'] = auth()->id();
        Campaign::create($validated);

        return redirect()->route('manager.campaigns.index')->with('success', 'Campaign created successfully!');
    }

    /**
     * Show edit campaign form.
     */
    public function campaignsEdit($id)
    {
        $campaign = Campaign::findOrFail($id);

        return view('manager.campaigns.edit', compact('campaign'));
    }

    /**
     * Update a campaign.
     */
    public function campaignsUpdate(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'donation_link' => 'nullable|url|max:500',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('image')) {
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->update($validated);

        return redirect()->route('manager.campaigns.index')->with('success', 'Campaign updated successfully!');
    }

    /**
     * Delete a campaign (only own campaigns).
     */
    public function campaignsDestroy($id)
    {
        $campaign = Campaign::findOrFail($id);

        // Only allow deletion of own campaigns
        if ($campaign->created_by !== auth()->id()) {
            return redirect()->route('manager.campaigns.index')
                ->with('error', 'You can only delete your own campaigns.');
        }

        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }

        $campaign->delete();

        return redirect()->route('manager.campaigns.index')->with('success', 'Campaign deleted successfully!');
    }

    /**
     * Show email form for sending emails to filtered alumni about campaign.
     */
    public function campaignsShowEmailForm(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);

        // Start with base query (excluding admins and managers)
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'manager']);
        });

        // Apply filters
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('enrollment_no', 'like', "%{$search}%")
                        ->orWhere('major', 'like', "%{$search}%")
                        ->orWhere('course', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('current_position', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('passing_year', 'like', "%{$search}%");
                });
            }
        }

        // Filter by passing year
        if ($request->filled('passing_year')) {
            $query->where('passing_year', $request->passing_year);
        }

        // Filter by status - default to active
        if ($request->filled('status') && $request->status === 'inactive') {
            $query->where('status', 'inactive');
        } else {
            $query->where('status', 'active');
        }

        // Filter by profile status
        if ($request->filled('profile_status')) {
            $query->where('profile_status', $request->profile_status);
        }

        $query->orderBy('name', 'asc');
        $recipients = $query->get();
        $recipientCount = $recipients->count();

        // Get passing years for filter dropdown
        $passingYears = User::whereNotNull('passing_year')
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['admin', 'manager']);
            })
            ->where('status', 'active')
            ->distinct()
            ->orderBy('passing_year', 'desc')
            ->pluck('passing_year');

        $statusFilter = $request->get('status', 'active');
        $profileStatusFilter = $request->get('profile_status', '');

        return view('manager.campaigns.email-form', compact('campaign', 'recipients', 'recipientCount', 'passingYears', 'statusFilter', 'profileStatusFilter'));
    }

    /**
     * Send email to filtered alumni about campaign.
     */
    public function campaignsSendEmail(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_ids' => 'required|array',
            'recipient_ids.*' => 'exists:users,id',
        ]);

        try {
            // Get recipients
            $recipients = User::whereIn('id', $validated['recipient_ids'])
                ->whereDoesntHave('roles', function ($q) {
                    $q->whereIn('name', ['admin', 'manager']);
                })
                ->where('status', 'active')
                ->get();

            // Queue all emails instead of sending synchronously
            $queuedCount = 0;
            foreach ($recipients as $recipient) {
                try {
                    Mail::to($recipient->email)->queue(new AlumniEmail($recipient, $validated['subject'], $validated['message']));
                    $queuedCount++;
                } catch (\Exception $e) {
                    \Log::error("Failed to queue campaign email to {$recipient->email}: ".$e->getMessage());
                }
            }

            return redirect()->route('manager.campaigns.index')
                ->with('success', "{$queuedCount} campaign emails have been queued for sending. Emails will be sent in the background.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to send emails: '.$e->getMessage()]);
        }
    }

    // ==================== Alumni Map ====================

    /**
     * Show alumni map.
     */
    public function alumniMap()
    {
        // Get passing years that have alumni
        $passingYears = User::whereNotNull('passing_year')
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['admin', 'manager']);
            })
            ->distinct()
            ->orderBy('passing_year', 'desc')
            ->pluck('passing_year');

        return view('manager.alumni.map', compact('passingYears'));
    }

    /**
     * Get alumni location data for map.
     */
    public function getAlumniLocations(Request $request)
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'manager']);
        })
            ->whereNotNull('residence_country')
            ->where(function ($q) {
                // Show alumni who have country AND (address OR city)
                $q->where(function ($subQ) {
                    $subQ->whereNotNull('residence_address')
                        ->where('residence_address', '!=', '')
                        ->whereRaw("TRIM(residence_address) != ''");
                })
                    ->orWhere(function ($subQ) {
                        $subQ->whereNotNull('residence_city')
                            ->where('residence_city', '!=', '')
                            ->whereRaw("TRIM(residence_city) != ''");
                    });
            });

        // Apply filters if provided
        if ($request->filled('passing_year')) {
            $query->where('passing_year', $request->passing_year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('profile_status')) {
            $query->where('profile_status', $request->profile_status);
        }

        $alumni = $query->select('id', 'name', 'email', 'residence_address', 'residence_city', 'residence_state', 'residence_country', 'passing_year', 'course', 'company', 'profile_image')
            ->get();

        // Group by location and prepare data
        $locations = [];
        foreach ($alumni as $alumnus) {
            // Build location string: prefer city+state+country, fallback to address+country if no city
            $locationParts = [];
            if ($alumnus->residence_city) {
                $locationParts[] = $alumnus->residence_city;
            }
            if ($alumnus->residence_state) {
                $locationParts[] = $alumnus->residence_state;
            }
            $locationParts[] = $alumnus->residence_country;

            $locationString = implode(', ', $locationParts);
            $locationKey = strtolower($locationString);

            if (! isset($locations[$locationKey])) {
                $locations[$locationKey] = [
                    'city' => $alumnus->residence_city,
                    'state' => $alumnus->residence_state,
                    'country' => $alumnus->residence_country,
                    'location_string' => $locationString,
                    'alumni' => [],
                ];
            }

            $locations[$locationKey]['alumni'][] = [
                'id' => $alumnus->id,
                'name' => $alumnus->name,
                'email' => $alumnus->email,
                'passing_year' => $alumnus->passing_year,
                'course' => $alumnus->course,
                'company' => $alumnus->company,
                'profile_image' => $alumnus->profile_image,
            ];
        }

        return response()->json([
            'locations' => array_values($locations),
            'total_alumni' => $alumni->count(),
        ]);
    }

    // ==================== CSV Export ====================

    /**
     * Export alumni data to CSV with filters applied.
     */
    public function exportAlumni(Request $request)
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'manager']);
        });

        // Apply the same filters as alumni directory
        // Search functionality
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('enrollment_no', 'like', "%{$search}%")
                        ->orWhere('major', 'like', "%{$search}%")
                        ->orWhere('course', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('current_position', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('passing_year', 'like', "%{$search}%");
                });
            }
        }

        // Filter by passing year
        if ($request->filled('passing_year')) {
            $query->where('passing_year', $request->passing_year);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by profile status
        if ($request->filled('profile_status')) {
            $query->where('profile_status', $request->profile_status);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        // Validate sort_by and sort_order
        $allowedSortFields = ['name', 'passing_year', 'course', 'company'];
        if (! in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'name';
        }
        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);

        $alumni = $query->get();

        $fileName = 'alumni_export_'.now()->format('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ];

        $callback = function () use ($alumni) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Name',
                'Enrollment No',
                'Email',
                'Alternate Email',
                'Phone',
                'Graduation Year',
                'Passing Year',
                'Major',
                'Course',
                'Residence Address',
                'Residence City',
                'Residence State',
                'Residence Country',
                'Aadhar Number',
                'Date of Birth',
                'Wedding Anniversary Date',
                'Bio',
                'Current Position',
                'Designation',
                'Company',
                'Employment Type',
                'Employment Address',
                'Employment City',
                'Employment State',
                'Employment Pincode',
                'LinkedIn URL',
                'Status',
                'Profile Status',
                'Profile Completed',
                'Profile Submitted At',
                'Created At',
            ]);

            // Add data rows
            foreach ($alumni as $alumnus) {
                fputcsv($file, [
                    $alumnus->name,
                    $alumnus->enrollment_no,
                    $alumnus->email,
                    $alumnus->alternate_email,
                    $alumnus->phone,
                    $alumnus->graduation_year,
                    $alumnus->passing_year,
                    $alumnus->major,
                    $alumnus->course,
                    $alumnus->residence_address,
                    $alumnus->residence_city,
                    $alumnus->residence_state,
                    $alumnus->residence_country,
                    $alumnus->aadhar_number,
                    $alumnus->date_of_birth?->format('Y-m-d'),
                    $alumnus->wedding_anniversary_date?->format('Y-m-d'),
                    $alumnus->bio,
                    $alumnus->current_position,
                    $alumnus->designation,
                    $alumnus->company,
                    $alumnus->employment_type,
                    $alumnus->employment_address,
                    $alumnus->employment_city,
                    $alumnus->employment_state,
                    $alumnus->employment_pincode,
                    $alumnus->linkedin_url,
                    $alumnus->status,
                    $alumnus->profile_status,
                    $alumnus->profile_completed ? 'Yes' : 'No',
                    $alumnus->profile_submitted_at?->format('Y-m-d H:i:s'),
                    $alumnus->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show bulk email form for filtered alumni.
     */
    public function showBulkEmailForm(Request $request)
    {
        // Start with base query (excluding admins and managers)
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'manager']);
        });

        // Apply same filters as directory
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('enrollment_no', 'like', "%{$search}%")
                        ->orWhere('major', 'like', "%{$search}%")
                        ->orWhere('course', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('current_position', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('passing_year', 'like', "%{$search}%");
                });
            }
        }

        // Filter by passing year
        if ($request->filled('passing_year')) {
            $query->where('passing_year', $request->passing_year);
        }

        // Filter by status - allow inactive only if explicitly filtered
        if ($request->filled('status') && $request->status === 'inactive') {
            $query->where('status', 'inactive');
        } else {
            // Default to active if no status filter or filter is 'active'
            $query->where('status', 'active');
        }

        // Filter by profile status
        if ($request->filled('profile_status')) {
            $query->where('profile_status', $request->profile_status);
        }

        // Apply same sorting as directory (if provided)
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        // Validate sort_by and sort_order
        $allowedSortFields = ['name', 'passing_year', 'course', 'company'];
        if (! in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'name';
        }
        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);

        $recipients = $query->get();
        $recipientCount = $recipients->count();
        $statusFilter = $request->get('status', 'active');

        return view('manager.alumni.bulk-email-form', compact('recipients', 'recipientCount', 'statusFilter'));
    }

    /**
     * Send email to bulk alumni (filtered list).
     */
    public function sendBulkEmail(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_ids' => 'required|array',
            'recipient_ids.*' => 'exists:users,id',
            'status_filter' => 'nullable|in:active,inactive',
        ]);

        try {
            // Get recipients based on the status filter
            $recipientsQuery = User::whereIn('id', $validated['recipient_ids'])
                ->whereDoesntHave('roles', function ($q) {
                    $q->whereIn('name', ['admin', 'manager']);
                });

            // Apply status filter if provided, otherwise default to active
            if (isset($validated['status_filter']) && $validated['status_filter'] === 'inactive') {
                $recipientsQuery->where('status', 'inactive');
            } else {
                $recipientsQuery->where('status', 'active');
            }

            $recipients = $recipientsQuery->get();

            // Queue all emails instead of sending synchronously
            $queuedCount = 0;
            foreach ($recipients as $recipient) {
                try {
                    Mail::to($recipient->email)->queue(new \App\Mail\AlumniEmail($recipient, $validated['subject'], $validated['message']));
                    $queuedCount++;
                } catch (\Exception $e) {
                    // Log error but continue with other recipients
                    \Log::error("Failed to queue email to {$recipient->email}: ".$e->getMessage());
                }
            }

            $statusText = ($validated['status_filter'] ?? 'active') === 'inactive' ? 'inactive' : 'active';

            return redirect()->route('manager.alumni.index')
                ->with('success', "{$queuedCount} emails have been queued for sending to {$statusText} alumni. Emails will be sent in the background.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to send emails: '.$e->getMessage()]);
        }
    }
}
