<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandingPageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'identifier',
        'config_schema',
    ];

    protected $casts = [
        'config_schema' => 'json',
    ];

    public function landingPages(): HasMany
    {
        return $this->hasMany(LandingPage::class);
    }
}
