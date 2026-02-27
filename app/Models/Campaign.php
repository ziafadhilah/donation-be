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

    protected $casts = [
        'goal_amount' => 'float',
        'current_amount' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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

    public function getProgressPercentageAttribute()
    {
        if ($this->goal_amount == 0) return 0;

        return round(($this->current_amount / $this->goal_amount) * 100, 2);
    }
}
