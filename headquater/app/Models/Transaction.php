<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'site_id',
        'price_id',
        'amount',
        'invoice_id',
        'currency',
        'gateway',
        'gateway_payment_id',
        'gateway_transaction_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function price()
    {
        return $this->belongsTo(Price::class);
    }
}