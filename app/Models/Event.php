<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the user who created this event
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get only published events
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to get upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now());
    }

    /**
     * Get invitations for this event
     */
    public function invitations()
    {
        return $this->hasMany(EventInvitation::class);
    }

    /**
     * Get registrations for this event
     */
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
