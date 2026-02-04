<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'event_date',
        'registration_start',
        'registration_end',
        'location',
        'city',
        'state',
        'max_participants',
        'status',
        'banner_image',
        'terms_and_conditions',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'status' => \App\Enums\EventStatus::class,
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class)->orderBy('sort_order');
    }

    public function orderItems(): HasManyThrough
    {
        return $this->hasManyThrough(OrderItem::class, Category::class);
    }

    public function customFields(): HasMany
    {
        return $this->hasMany(EventCustomField::class)->orderBy('sort_order');
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(EventCoupon::class);
    }

    public function routes(): HasMany
    {
        return $this->hasMany(EventRoute::class);
    }
}
