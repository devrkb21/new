<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'is_public', // Added this line
        'limit_checkouts',
        'limit_fraud_ips',
        'limit_fraud_emails',
        'limit_fraud_phones',
        'limit_courier_checks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_public' => 'boolean', // Added this cast for data consistency
    ];

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}