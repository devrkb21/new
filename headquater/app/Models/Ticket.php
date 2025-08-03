<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'site_id', // <-- ADD THIS LINE
        'title',
        'message',
        'status',
        'priority',
        'attachment_path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ADD THIS NEW RELATIONSHIP
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at');
    }
}