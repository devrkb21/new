<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingPayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'payment_id',
        'amount',
    ];

    /**
     * Get the user who initiated the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}