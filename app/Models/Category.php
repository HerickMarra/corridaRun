<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'distance',
        'price',
        'max_participants',
        'min_age',
        'max_age',
        'available_tickets',
        'status',
        'sort_order',
        'is_public',
        'access_hash',
        'items_included',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_public' => 'boolean',
        'items_included' => 'array',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getAvailableTicketsAttribute(): int
    {
        $soldCount = $this->orderItems()->where('status', 'paid')->count();
        return max(0, $this->max_participants - $soldCount);
    }
}
