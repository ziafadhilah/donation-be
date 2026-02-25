<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundRealization extends Model
{
    protected $fillable = [
        'campaign_id',
        'title',
        'description',
        'amount',
        'status'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
