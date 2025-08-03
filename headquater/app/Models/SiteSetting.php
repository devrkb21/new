<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'checkout_tracking_enabled',
        'fraud_blocker_enabled',
        'courier_service_enabled',
        'data_retention_days',
    ];

    /**
     * Get the site that owns the settings.
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}