<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['service', 'topic', 'payload', 'expired_at', 'subscriber_id'];

    // Указываем, что payload это JSON (array)
    protected $casts = [
        'payload' => 'array',
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}