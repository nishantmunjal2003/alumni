<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'description',
        'image',
        'event_date',
        'location',
        'latitude',
        'longitude',
        'venue',
        'status',
        'target_graduation_years',
        'invites_sent',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'target_graduation_years' => 'array',
        'invites_sent' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(EventInvitation::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }
}
