<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        // Use a single query with selectRaw to get multiple counts efficiently
        // total_users counts ALL users regardless of status (active, inactive, or any other status)
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
            'recent_registrations' => User::latest()->limit(5)->get(),
        ];

        $activeCampaigns = Campaign::where('status', 'published')
            ->where('end_date', '>=', now())
            ->with('creator')
            ->orderBy('start_date', 'desc')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'activeCampaigns'));
    }

    public function users(Request $request)
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

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        // Exclude 'user' role - all users are alumni by default
        $roles = Role::where('name', '!=', 'user')->get();

        // Pre-compute user roles to avoid N+1 queries in the view
        $userRoles = [];
        foreach ($users as $user) {
            $userRoles[$user->id] = $user->roles->pluck('name')->toArray();
        }

        return view('admin.users.index', compact('users', 'roles', 'userRoles'));
    }

    public function updateUserRoles(Request $request, User $user)
    {
        // Prevent removing admin role from the protected admin account
        if ($user->email === 'nishant@gkv.ac.in') {
            $validated = $request->validate([
                'roles' => 'array',
            ]);

            $roles = $validated['roles'] ?? [];

            // Ensure admin role is always present
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole && ! in_array('admin', $roles)) {
                $roles[] = 'admin';
            }

            $user->syncRoles($roles);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'User roles updated successfully! Note: Admin role is protected for this account.']);
            }

            return back()->with('success', 'User roles updated successfully! Note: Admin role is protected for this account.');
        }

        $validated = $request->validate([
            'roles' => 'array',
        ]);

        $roles = $validated['roles'] ?? [];
        $user->syncRoles($roles);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'User roles updated successfully!']);
        }

        return back()->with('success', 'User roles updated successfully!');
    }

    /**
     * Toggle user status (activate/deactivate).
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating the protected admin account
        if ($user->email === 'nishant@gkv.ac.in') {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'This admin account cannot be deactivated.'], 403);
            }

            return back()->with('error', 'This admin account cannot be deactivated.');
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';

        $user->update([
            'status' => $newStatus,
            'profile_status' => $newStatus === 'active' ? 'approved' : 'blocked',
        ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'User status updated successfully!', 'status' => $newStatus]);
        }

        return back()->with('success', 'User status updated successfully!');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting the currently logged-in admin
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting other admins (optional safety check)
        if ($user->hasRole('admin') && ! auth()->user()->hasRole('admin')) {
            return back()->with('error', 'You do not have permission to delete admin users.');
        }

        $userName = $user->name;
        $user->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'User deleted successfully!']);
        }

        return redirect()->route('admin.users.index')->with('success', "User {$userName} has been deleted successfully!");
    }

    public function roles()
    {
        $roles = Role::with('permissions')->get();

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

        Role::create(['name' => $validated['name'], 'guard_name' => 'web']);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully!');
    }

    public function destroyRole(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully!');
    }

    /**
     * Show pending profiles for approval.
     */
    public function pendingProfiles()
    {
        $pendingProfiles = User::where('profile_status', 'pending')
            ->where('profile_completed', true)
            ->latest()
            ->paginate(20);

        $totalPendingCount = User::where('profile_status', 'pending')
            ->where('profile_completed', true)
            ->count();

        return view('admin.profiles.pending', compact('pendingProfiles', 'totalPendingCount'));
    }

    /**
     * Approve a user profile.
     */
    public function approveProfile(User $user)
    {
        if (! $user->profile_completed) {
            return back()->withErrors(['error' => 'Profile is not completed yet.']);
        }

        // Check if proof document is uploaded
        if (! $user->proof_document) {
            return back()->withErrors(['error' => 'Cannot approve profile without proof document. Please ask the user to upload proof document first.']);
        }

        $user->update([
            'profile_status' => 'approved',
            'status' => 'active',
        ]);

        return back()->with('success', 'Profile approved successfully!');
    }

    /**
     * Block a user profile.
     */
    public function blockProfile(User $user)
    {
        $user->update([
            'profile_status' => 'blocked',
            'status' => 'inactive',
        ]);

        return back()->with('success', 'Profile blocked successfully!');
    }

    /**
     * View a specific profile for approval.
     */
    public function viewProfile(User $user)
    {
        return view('admin.profiles.view', compact('user'));
    }

    /**
     * Show form to edit alumni data.
     */
    public function editAlumni(User $user)
    {
        $countries = $this->getCountries();
        $courses = $this->getCourses();
        $years = $this->getYears();

        return view('admin.alumni.edit', compact('user', 'countries', 'courses', 'years'));
    }

    /**
     * Get list of years from current year down to 1980.
     */
    private function getYears(): array
    {
        $currentYear = (int) date('Y');
        $years = [];

        for ($year = $currentYear; $year >= 1980; $year--) {
            $years[] = $year;
        }

        return $years;
    }

    /**
     * Get list of courses organized by degree level.
     */
    private function getCourses(): array
    {
        return [
            'PhD Courses' => [
                'Mathematics', 'Chemistry', 'Physics', 'Microbiology', 'Environmental Science',
                'Management', 'Computer Science', 'Zoology', 'Botany', 'Sanskrit Literature',
                'Vedic Literature', 'Ancient Indian History, Culture and Archaeology', 'English',
                'Psychology', 'Philosophy', 'Human Consciousness and Yogic Science', 'Hindi Literature',
            ],
            'Postgraduate (PG) Courses' => [
                'Master in Computer Application (MCA)', 'Master in Business Administration (MBA)',
                'Master in Business Finance (MBF)', 'Master in Business Economics (MBE)',
                'M.Sc. (Microbiology)', 'M.Sc. (Chemistry)', 'M.Sc. (Environmental Science)',
                'M.Sc. (Mathematics)', 'M.Sc. (Physics)', 'M.A. (Sanskrit)', 'M.A. (Ved)',
                'M.A. (Ancient Indian History, Culture and Archaeology)', 'M.A. (Philosophy)',
                'M.A. (Hindi)', 'M.A. (English)', 'M.A. (Psychology)', 'Human Consciousness and Yogic Science',
                'Dharmshastra Vedic Karmkand and Jyotish', 'M.P.Ed',
            ],
            'Undergraduate (UG) Courses' => [
                'B.Tech. (ECE)', 'B.Tech. (CSE)', 'B.Tech. (EE)', 'B.Tech. (ME)', 'B.Pharma',
                'B.Sc. (Physics, Chemistry, Math)', 'B.Sc. (Physics, Computer, Math)',
                'B.Sc. (Computer, Economics, Math)', 'B.Sc. (Botany, Chemistry, Zoology)',
                'B.Sc. (Zoology, Chemistry, Industrial Micro)', 'B.Sc. (Botany, Chemistry, Industrial Micro)',
                'BBA', 'BA (Vidyalankar)', 'BA (Vedalankar)', 'B.P.Ed.', 'D.Pharm',
            ],
        ];
    }

    /**
     * Get list of countries.
     */
    private function getCountries(): array
    {
        return [
            'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria',
            'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan',
            'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia',
            'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo', 'Costa Rica',
            'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt',
            'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon',
            'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana',
            'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel',
            'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kosovo', 'Kuwait', 'Kyrgyzstan',
            'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Madagascar',
            'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia',
            'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal',
            'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea', 'North Macedonia', 'Norway', 'Oman', 'Pakistan',
            'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar',
            'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia',
            'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa',
            'South Korea', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan',
            'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan',
            'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City',
            'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe',
        ];
    }

    /**
     * Show alumni directory for admin with search functionality.
     */
    public function alumniDirectory(Request $request)
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
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
            $q->where('name', 'admin');
        })->count();

        // Get passing years for filter
        $passingYears = User::whereNotNull('passing_year')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            })
            ->distinct()
            ->orderBy('passing_year', 'desc')
            ->pluck('passing_year');

        if ($request->ajax()) {
            return view('admin.alumni.partials.alumni-list', compact('alumni', 'sortBy', 'sortOrder'))->render();
        }

        return view('admin.alumni.index', compact('alumni', 'passingYears', 'totalAlumniCount', 'sortBy', 'sortOrder'));
    }

    /**
     * View a specific alumni profile.
     */
    public function viewAlumni(User $user)
    {
        return view('admin.alumni.view', compact('user'));
    }

    /**
     * Show email compose form for single alumni.
     */
    public function showEmailForm(User $user)
    {
        return view('admin.alumni.email-form', compact('user'));
    }

    /**
     * Send email to a single alumni.
     */
    public function sendEmailToAlumni(Request $request, User $user)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\AlumniEmail($user, $validated['subject'], $validated['message']));

            return redirect()->route('admin.alumni.view', $user->id)
                ->with('success', 'Email sent successfully to '.$user->name);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to send email: '.$e->getMessage()]);
        }
    }

    /**
     * Show email compose form for bulk alumni.
     */
    public function showBulkEmailForm(Request $request)
    {
        // Start with the exact same base query as alumniDirectory
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
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

        return view('admin.alumni.bulk-email-form', compact('recipients', 'recipientCount', 'statusFilter'));
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
                    $q->where('name', 'admin');
                });

            // Apply status filter if provided, otherwise default to active
            if (isset($validated['status_filter']) && $validated['status_filter'] === 'inactive') {
                $recipientsQuery->where('status', 'inactive');
            } else {
                $recipientsQuery->where('status', 'active');
            }

            $recipients = $recipientsQuery->get();

            $sentCount = 0;
            foreach ($recipients as $recipient) {
                try {
                    Mail::to($recipient->email)->send(new \App\Mail\AlumniEmail($recipient, $validated['subject'], $validated['message']));
                    $sentCount++;
                } catch (\Exception $e) {
                    // Log error but continue with other recipients
                    \Log::error("Failed to send email to {$recipient->email}: ".$e->getMessage());
                }
            }

            $statusText = ($validated['status_filter'] ?? 'active') === 'inactive' ? 'inactive' : 'active';

            return redirect()->route('admin.alumni.index')
                ->with('success', "Email sent successfully to {$sentCount} out of {$recipients->count()} {$statusText} alumni.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to send emails: '.$e->getMessage()]);
        }
    }

    /**
     * Update alumni data.
     */
    public function updateAlumni(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'graduation_year' => 'nullable|string|max:10',
            'passing_year' => 'nullable|string|max:10',
            'major' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'residence_address' => 'nullable|string',
            'residence_city' => 'nullable|string|max:255',
            'residence_state' => 'nullable|string|max:255',
            'residence_country' => 'nullable|string|max:255',
            'aadhar_number' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'wedding_anniversary_date' => 'nullable|date',
            'bio' => 'nullable|string',
            'current_position' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'employment_address' => 'nullable|string',
            'employment_city' => 'nullable|string|max:255',
            'employment_state' => 'nullable|string|max:255',
            'employment_pincode' => 'nullable|string|max:10',
            'alternate_email' => 'nullable|email|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
            'profile_status' => 'required|in:pending,approved,blocked',
            'profile_completed' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Alumni data updated successfully!');
    }

    /**
     * Export alumni data to CSV with filters applied.
     */
    public function exportAlumni(Request $request)
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        });

        // Apply the same filters as alumniDirectory
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
}
