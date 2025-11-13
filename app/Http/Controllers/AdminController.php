<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'total_campaigns' => Campaign::count(),
            'pending_profiles' => User::where('profile_status', 'pending')
                ->where('profile_completed', true)
                ->count(),
            'recent_registrations' => User::latest()->limit(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
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

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateUserRoles(Request $request, User $user)
    {
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

        return view('admin.profiles.pending', compact('pendingProfiles'));
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

        return redirect()->route('admin.users.index')->with('success', 'Alumni data updated successfully!');
    }
}
