<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'admin_email',
        'user_id',
        'plan_id',
        'price_id',
        'status',
        'plan_expires_at',
        'plan_activated_at',
        'next_plan_id',
        'next_price_id',
        'plan_change_at',
        'custom_limits',
        'custom_price_amount',
        'custom_billing_period_id',
    ];

    protected function casts(): array
    {
        return [
            'plan_expires_at' => 'datetime',
            'plan_activated_at' => 'datetime',
            'plan_change_at' => 'datetime',
            'custom_limits' => 'array',
        ];
    }

    /**
     * Get the effective limit for a given feature.
     */
    public function getLimit(string $featureKey): ?int
    {
        // If the site is on a custom plan, custom limits are the only source of truth.
        if ($this->plan?->slug === 'custom') {
            return (int) ($this->custom_limits[$featureKey] ?? 0);
        }

        // Otherwise, check for a custom limit override first.
        if (isset($this->custom_limits[$featureKey]) && $this->custom_limits[$featureKey] !== null) {
            return (int) $this->custom_limits[$featureKey];
        }

        // Fallback to the standard plan's limit.
        if ($this->plan) {
            return (int) $this->plan->{$featureKey};
        }

        return 0;
    }

    /**
     * Get the name of the effective plan.
     */
    public function getPlanNameAttribute(): string
    {
        return $this->plan->name ?? 'N/A';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    public function price()
    {
        return $this->belongsTo(Price::class);
    }
    public function settings()
    {
        return $this->hasOne(SiteSetting::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function nextPlan()
    {
        return $this->belongsTo(Plan::class, 'next_plan_id');
    }
    public function nextPrice()
    {
        return $this->belongsTo(Price::class, 'next_price_id');
    }

    public function customBillingPeriod()
    {
        return $this->belongsTo(BillingPeriod::class, 'custom_billing_period_id');
    }
}