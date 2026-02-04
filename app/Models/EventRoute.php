<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'color',
        'path',
    ];

    protected $casts = [
        'path' => 'array',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
