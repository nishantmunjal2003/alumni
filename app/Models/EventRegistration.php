<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'arrival_date',
        'coming_from_city',
        'arrival_time',
        'needs_stay',
        'coming_with_family',
        'travel_mode',
        'return_journey_details',
        'memories_description',
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'arrival_time' => 'datetime',
        'needs_stay' => 'boolean',
        'coming_with_family' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(EventRegistrationPhoto::class);
    }

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_registration_friends')
            ->withTimestamps();
    }
}
