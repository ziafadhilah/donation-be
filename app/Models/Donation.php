<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'campaign_id',
        'unit_id',
        'unit_qty',
        'name',
        'phone',
        'email',
        'is_anonymous',
        'amount',
        'payment_method',
        'reference',
        'status',
        'failure_reason',
        'duitku_reference',
        'callback_payload',
        'paid_at',
        'expired_at',
        'is_visible'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_anonymous' => 'boolean',
        'callback_payload' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function getMaskedPhoneAttribute()
    {
        return substr($this->phone, 0, 4)
            . '****'
            . substr($this->phone, -2);
    }

    public function getMaskedEmailAttribute()
    {
        if (!$this->email) return null;

        $parts = explode('@', $this->email);

        if (count($parts) < 2) return $this->email;

        return substr($parts[0], 0, 2)
            . '****@'
            . $parts[1];
    }
}
