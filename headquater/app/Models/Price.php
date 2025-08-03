<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'billing_period_id',
        'amount',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function billingPeriod()
    {
        return $this->belongsTo(BillingPeriod::class);
    }
}