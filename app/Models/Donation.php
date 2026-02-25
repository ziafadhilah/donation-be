<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'campaign_id',
        'name',
        'phone',
        'email',
        'amount',
        'payment_method',
        'reference',
        'status',
        'failure_reason',
        'duitku_reference',
        'callback_payload',
        'is_visible'
    ];

    protected $casts = [
        'is_visible' => 'boolean'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getMaskedPhoneAttribute()
    {
        return substr($this->phone, 0, 4)
            . '****'
            . substr($this->phone, -2);
    }

    public function getMaskedEmailAttribute()
    {
        $parts = explode('@', $this->email);

        if (count($parts) < 2) return $this->email;

        return substr($parts[0], 0, 2)
            . '****@'
            . $parts[1];
    }
}
