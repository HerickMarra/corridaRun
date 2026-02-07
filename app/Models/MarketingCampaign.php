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
        'status',
        'total_recipients',
        'sent_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'sent_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }
}
