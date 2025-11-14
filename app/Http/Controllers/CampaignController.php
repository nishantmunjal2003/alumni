<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('status', 'published')
            ->where('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->paginate(12);

        return view('campaigns.index', compact('campaigns'));
    }

    public function show($id)
    {
        $campaign = Campaign::with('creator')->findOrFail($id);

        return view('campaigns.show', compact('campaign'));
    }

    public function adminIndex()
    {
        $campaigns = Campaign::with('creator')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
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

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign created successfully!');
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);

        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, $id)
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

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign updated successfully!');
    }

    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);

        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign deleted successfully!');
    }

    /**
     * Show email compose form for campaign announcement to all alumni.
     */
    public function showBulkEmailForm(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);

        // Start with base query (excluding admins)
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
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

        // Filter by profile status - no default, include all if not specified
        if ($request->filled('profile_status')) {
            $query->where('profile_status', $request->profile_status);
        }

        $query->orderBy('name', 'asc');
        $recipients = $query->get();
        $recipientCount = $recipients->count();

        // Get passing years for filter dropdown
        $passingYears = User::whereNotNull('passing_year')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            })
            ->where('status', 'active')
            ->distinct()
            ->orderBy('passing_year', 'desc')
            ->pluck('passing_year');

        $statusFilter = $request->get('status', 'active');
        $profileStatusFilter = $request->get('profile_status', '');

        return view('admin.campaigns.bulk-email-form', compact('campaign', 'recipients', 'recipientCount', 'passingYears', 'statusFilter', 'profileStatusFilter'));
    }

    /**
     * Send campaign announcement email to all alumni.
     */
    public function sendBulkEmail(Request $request, $id)
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
                    $q->where('name', 'admin');
                })
                ->where('status', 'active')
                ->get();

            $sentCount = 0;
            foreach ($recipients as $recipient) {
                try {
                    Mail::to($recipient->email)->send(new \App\Mail\AlumniEmail($recipient, $validated['subject'], $validated['message']));
                    $sentCount++;
                } catch (\Exception $e) {
                    // Log error but continue with other recipients
                    \Log::error("Failed to send campaign email to {$recipient->email}: ".$e->getMessage());
                }
            }

            return redirect()->route('admin.campaigns.index')
                ->with('success', "Campaign announcement sent successfully to {$sentCount} out of {$recipients->count()} alumni.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to send emails: '.$e->getMessage()]);
        }
    }
}
