<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'category_id',
        'participant_name',
        'participant_cpf',
        'participant_email',
        'participant_birth_date',
        'participant_phone',
        'shirt_size',
        'special_needs',
        'price',
        'status',
        'custom_responses',
    ];

    protected $casts = [
        'participant_birth_date' => 'date',
        'price' => 'decimal:2',
        'custom_responses' => 'json',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ticket(): HasOne
    {
        return $this->hasOne(Ticket::class);
    }
}
