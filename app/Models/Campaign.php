<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{

    protected $fillable = [
        'title',
        'description',
        'goal_amount',
        'current_amount',
        'start_date',
        'end_date',
        'status'
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function fundRealizations()
    {
        return $this->hasMany(FundRealization::class);
    }

    public function getTotalRealizedAttribute()
    {
        return $this->fundRealizations()
            ->where('status', 'done')
            ->sum('amount');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->current_amount - $this->total_realized;
    }
}
