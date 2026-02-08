<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'recipient_email',
        'user_id',
        'subject',
        'body',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
