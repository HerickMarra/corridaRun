<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventTag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'color_hex'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (!$tag->slug) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
