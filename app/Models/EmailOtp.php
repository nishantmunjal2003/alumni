<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Generate a 6-digit OTP.
     */
    public static function generateOtp(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new OTP for the given email.
     */
    public static function createForEmail(string $email): self
    {
        // Invalidate any existing unverified OTPs for this email
        self::where('email', $email)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->update(['is_verified' => true]);

        return self::create([
            'email' => $email,
            'otp' => self::generateOtp(),
            'expires_at' => now()->addMinutes(10),
            'is_verified' => false,
        ]);
    }

    /**
     * Verify the OTP for the given email.
     */
    public static function verify(string $email, string $otp): bool
    {
        $emailOtp = self::where('email', $email)
            ->where('otp', $otp)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($emailOtp) {
            $emailOtp->update(['is_verified' => true]);

            return true;
        }

        return false;
    }

    /**
     * Check if OTP is valid (not expired and not verified).
     */
    public function isValid(): bool
    {
        return ! $this->is_verified && $this->expires_at->isFuture();
    }
}
