<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'url',
        'method',
        'user_agent',
        'country',
        'city',
        'status_code',
        'exception'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
