<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingCampaign extends Model
{
    protected $fillable = [
        'name',
        'subject',
        'content',
        'template_id',
        'filters',
        'scheduled_at',
        'status',
        'total_recipients',
        'processed_recipients',
        'sent_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }
}
