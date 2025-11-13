<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'enrollment_no',
        'email',
        'password',
        'phone',
        'graduation_year',
        'passing_year',
        'major',
        'course',
        'proof_document',
        'residence_address',
        'residence_city',
        'residence_state',
        'residence_country',
        'aadhar_number',
        'date_of_birth',
        'wedding_anniversary_date',
        'bio',
        'current_position',
        'designation',
        'company',
        'employment_type',
        'employment_address',
        'employment_city',
        'employment_state',
        'employment_pincode',
        'alternate_email',
        'linkedin_url',
        'profile_image',
        'status',
        'profile_completed',
        'profile_status',
        'profile_submitted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'profile_completed' => 'boolean',
            'date_of_birth' => 'date',
            'wedding_anniversary_date' => 'date',
            'profile_submitted_at' => 'datetime',
        ];
    }

    // Relationships
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'created_by');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'from_user_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }

    public function eventInvitations()
    {
        return $this->hasMany(EventInvitation::class);
    }

    public function batchmates()
    {
        if (! $this->graduation_year) {
            return User::whereRaw('1 = 0'); // Return empty query if no graduation year
        }

        return User::where('graduation_year', $this->graduation_year)
            ->where('id', '!=', $this->id)
            ->where('status', 'active');
    }

    /**
     * Check if profile is completed with all required fields.
     */
    public function isProfileComplete(): bool
    {
        return $this->profile_completed &&
            $this->enrollment_no &&
            $this->passing_year &&
            $this->course &&
            $this->residence_address &&
            $this->residence_city &&
            $this->residence_state &&
            $this->residence_country &&
            $this->company &&
            $this->designation &&
            $this->employment_type &&
            $this->employment_address &&
            $this->employment_city &&
            $this->employment_state &&
            $this->employment_pincode &&
            $this->phone;
    }

    /**
     * Check if profile is approved by admin.
     */
    public function isProfileApproved(): bool
    {
        return $this->profile_status === 'approved';
    }

    /**
     * Check if profile is blocked by admin.
     */
    public function isProfileBlocked(): bool
    {
        return $this->profile_status === 'blocked';
    }

    /**
     * Check if user can access dashboard.
     * Users can access dashboard immediately after profile completion.
     * Admins and managers can always access dashboard without profile completion.
     */
    public function canAccessDashboard(): bool
    {
        // Admins and managers can always access dashboard
        if ($this->hasRole('admin') || $this->hasRole('manager')) {
            return true;
        }

        return $this->isProfileComplete() && ! $this->isProfileBlocked();
    }

    /**
     * Check if account should be deactivated due to missing proof document.
     */
    public function shouldBeDeactivatedForMissingProof(): bool
    {
        if ($this->proof_document) {
            return false;
        }

        if (! $this->profile_submitted_at) {
            return false;
        }

        return $this->profile_submitted_at->copy()->addDays(7)->isPast();
    }

    /**
     * Get days remaining before deactivation if proof is missing.
     */
    public function getDaysUntilDeactivation(): ?int
    {
        if ($this->proof_document || ! $this->profile_submitted_at) {
            return null;
        }

        $deactivationDate = $this->profile_submitted_at->copy()->addDays(7);
        $daysRemaining = now()->diffInDays($deactivationDate, false);

        return $daysRemaining > 0 ? $daysRemaining : 0;
    }
}
