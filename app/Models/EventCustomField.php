<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCustomField extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'label',
        'type',
        'options',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'sort_order' => 'integer',
        'options' => 'array', // Salvamos como JSON no banco para facilitar
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
