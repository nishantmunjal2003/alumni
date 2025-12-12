<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Mail\MissingProfileDetailsMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DataEntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the DataEntry dashboard with pending profiles.
     */
    public function dashboard(Request $request)
    {
        $query = User::where('profile_status', 'pending')
            ->whereDoesntHave('roles', function ($q) {
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
                        ->orWhere('course', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('passing_year', 'like', "%{$search}%");
                });
            }
        }

        // Filter by proof document status
        if ($request->filled('proof_filter')) {
            $proofFilter = $request->proof_filter;
            if ($proofFilter === 'uploaded') {
                $query->where(function ($q) {
                    $q->whereNotNull('proof_document')
                        ->where('proof_document', '!=', '')
                        ->whereRaw("TRIM(proof_document) != ''");
                });
            } elseif ($proofFilter === 'missing') {
                $query->where(function ($q) {
                    $q->whereNull('proof_document')
                        ->orWhere('proof_document', '')
                        ->orWhereRaw("TRIM(COALESCE(proof_document, '')) = ''");
                });
            }
        }

        $pendingProfiles = $query->latest()->paginate(20)->withQueryString();
        $totalPendingCount = User::where('profile_status', 'pending')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            })
            ->count();

        // Check if this is an AJAX request
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('dataentry.partials.pending-list', compact('pendingProfiles'))->render();
        }

        return view('dataentry.dashboard', compact('pendingProfiles', 'totalPendingCount'));
    }

    /**
     * Display a listing of pending profiles.
     */
    public function index(Request $request)
    {
        return $this->dashboard($request);
    }

    /**
     * Display the specified profile.
     */
    public function show(User $user): \Illuminate\View\View
    {
        $missingFields = $this->getMissingProfileFields($user);

        return view('dataentry.show', compact('user', 'missingFields'));
    }

    /**
     * Get missing profile fields for a user.
     *
     * @return array<string>
     */
    private function getMissingProfileFields(User $user): array
    {
        $missingFields = [];

        $requiredFields = [
            'passing_year' => 'Passing Year',
            'course' => 'Course/Major',
            'residence_address' => 'Current Residence Address',
            'residence_city' => 'City',
            'residence_state' => 'State',
            'residence_country' => 'Country',
            'company' => 'Company Name',
            'designation' => 'Designation',
            'employment_type' => 'Employment Type',
            'phone' => 'Phone Number',
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($user->$field)) {
                $missingFields[] = $label;
            }
        }

        return $missingFields;
    }

    /**
     * Show the form for editing the specified profile.
     */
    public function edit(User $user): \Illuminate\View\View
    {
        $countries = $this->getCountries();
        $courses = $this->getCourses();
        $years = $this->getYears();

        return view('dataentry.edit', compact('user', 'countries', 'courses', 'years'));
    }

    /**
     * Update the specified profile.
     */
    public function update(StoreProfileRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
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

        return redirect()->route('dataentry.show', $user->id)->with('success', 'Profile updated successfully!');
    }

    /**
     * Approve a user profile.
     */
    public function approve(User $user): \Illuminate\Http\RedirectResponse
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
    public function block(User $user): \Illuminate\Http\RedirectResponse
    {
        $user->update([
            'profile_status' => 'blocked',
            'status' => 'inactive',
        ]);

        return back()->with('success', 'Profile blocked successfully!');
    }

    /**
     * Show the email form for sending missing profile details notification.
     */
    public function showEmailForm(User $user): \Illuminate\View\View
    {
        $missingFields = $this->getMissingProfileFields($user);
        $profileUrl = route('profile.edit');

        // Create default email message
        $defaultMessage = $this->generateDefaultEmailMessage($user, $missingFields, $profileUrl);

        return view('dataentry.email', compact('user', 'missingFields', 'defaultMessage'));
    }

    /**
     * Send email to user about missing profile details.
     */
    public function sendEmail(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::to($user->email)->send(
                new MissingProfileDetailsMail(
                    $user,
                    $request->input('subject'),
                    $request->input('message')
                )
            );

            return redirect()->route('dataentry.profiles.show', $user->id)
                ->with('success', 'Email sent successfully to '.$user->email);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to send email: '.$e->getMessage()]);
        }
    }

    /**
     * Generate default email message for missing profile details.
     */
    private function generateDefaultEmailMessage(User $user, array $missingFields, string $profileUrl): string
    {
        $message = "Dear {$user->name},\n\n";
        $message .= "We hope this message finds you well.\n\n";
        $message .= "We noticed that your alumni profile is incomplete. To ensure your profile is accurate and up-to-date, we kindly request you to complete the following missing details:\n\n";

        if (! empty($missingFields)) {
            foreach ($missingFields as $field) {
                $message .= "• {$field}\n";
            }
        } else {
            $message .= "• Your profile appears to be complete, but please review and ensure all information is accurate.\n";
        }

        $message .= "\nPlease click the link below to complete your profile:\n";
        $message .= $profileUrl."\n\n";
        $message .= "Completing your profile will help us maintain accurate records and keep you connected with the alumni community.\n\n";
        $message .= "If you have any questions or need assistance, please feel free to reach out to us.\n\n";
        $message .= "Best regards,\n";
        $message .= 'Alumni Management Team';

        return $message;
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
