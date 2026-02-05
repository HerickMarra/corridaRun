<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LandingPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'landing_page_template_id',
        'content',
        'is_active',
    ];

    protected $casts = [
        'content' => 'json',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lp) {
            if (empty($lp->slug)) {
                $lp->slug = Str::slug($lp->title);
            }
        });
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(LandingPageTemplate::class, 'landing_page_template_id');
    }
}
