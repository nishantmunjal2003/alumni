<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
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
        $batchmates = User::where('passing_year', $user->passing_year)
            ->where('id', '!=', $user->id)
            ->where('status', 'active')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->with('roles')
            ->limit(10)
            ->get();

        $upcomingEvents = \App\Models\Event::where('status', 'published')
            ->where('event_start_date', '>=', now())
            ->withCount('registrations')
            ->orderBy('event_start_date', 'asc')
            ->limit(5)
            ->get();

        // Check for missing optional fields
        $missingEnrollmentNo = ! $user->enrollment_no;
        $missingProofDocument = ! $user->proof_document;

        return view('alumni.dashboard', compact('batchmates', 'upcomingEvents', 'missingEnrollmentNo', 'missingProofDocument'));
    }

    public function index(Request $request)
    {
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'alumni');
        });

        // Only apply search filter if search term is not empty
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

        if ($request->filled('passing_year')) {
            $query->where('passing_year', $request->passing_year);
        }

        $alumni = $query->orderBy('name', 'asc')->paginate(12);

        if ($request->ajax()) {
            return view('alumni.partials.alumni-list', compact('alumni'))->render();
        }

        // Only show passing years from alumni
        $passingYears = User::whereNotNull('passing_year')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'alumni');
            })
            ->distinct()
            ->orderBy('passing_year', 'desc')
            ->pluck('passing_year');

        return view('alumni.index', compact('alumni', 'passingYears'));
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

        $countries = $this->getCountries();
        $courses = $this->getCourses();
        $years = $this->getYears();

        return view('alumni.edit', compact('user', 'countries', 'courses', 'years'));
    }

    public function update(StoreProfileRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();

        // Handle proof document upload
        if ($request->hasFile('proof_document')) {
            if ($user->proof_document) {
                Storage::disk('public')->delete($user->proof_document);
            }
            $validated['proof_document'] = $request->file('proof_document')->store('proof-documents', 'public');
        } else {
            // Keep existing proof document if no new file uploaded
            $validated['proof_document'] = $user->proof_document;
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        } else {
            // Keep existing profile image if no new file uploaded
            $validated['profile_image'] = $user->profile_image;
        }

        $user->update($validated);

        return redirect()->route('alumni.show', $user->id)->with('success', 'Profile updated successfully!');
    }

    /**
     * Get list of years from current year down to 1980.
     *
     * @return array<int>
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
     *
     * @return array<string, array<string>>
     */
    private function getCourses(): array
    {
        return [
            'PhD Courses' => [
                'Mathematics',
                'Chemistry',
                'Physics',
                'Microbiology',
                'Environmental Science',
                'Management',
                'Computer Science',
                'Zoology',
                'Botany',
                'Sanskrit Literature',
                'Vedic Literature',
                'Ancient Indian History, Culture and Archaeology',
                'English',
                'Psychology',
                'Philosophy',
                'Human Consciousness and Yogic Science',
                'Hindi Literature',
            ],
            'Postgraduate (PG) Courses' => [
                'Master in Computer Application (MCA)',
                'Master in Business Administration (MBA)',
                'Master in Business Finance (MBF)',
                'Master in Business Economics (MBE)',
                'M.Sc. (Microbiology)',
                'M.Sc. (Chemistry)',
                'M.Sc. (Environmental Science)',
                'M.Sc. (Mathematics)',
                'M.Sc. (Physics)',
                'M.A. (Sanskrit)',
                'M.A. (Ved)',
                'M.A. (Ancient Indian History, Culture and Archaeology)',
                'M.A. (Philosophy)',
                'M.A. (Hindi)',
                'M.A. (English)',
                'M.A. (Psychology)',
                'Human Consciousness and Yogic Science',
                'Dharmshastra Vedic Karmkand and Jyotish',
                'M.P.Ed',
            ],
            'Undergraduate (UG) Courses' => [
                'B.Tech. (ECE)',
                'B.Tech. (CSE)',
                'B.Tech. (EE)',
                'B.Tech. (ME)',
                'B.Pharma',
                'B.Sc. (Physics, Chemistry, Math)',
                'B.Sc. (Physics, Computer, Math)',
                'B.Sc. (Computer, Economics, Math)',
                'B.Sc. (Botany, Chemistry, Zoology)',
                'B.Sc. (Zoology, Chemistry, Industrial Micro)',
                'B.Sc. (Botany, Chemistry, Industrial Micro)',
                'BBA',
                'BA (Vidyalankar)',
                'BA (Vedalankar)',
                'B.P.Ed.',
                'D.Pharm',
            ],
        ];
    }

    /**
     * Get list of countries.
     *
     * @return array<string>
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
}
