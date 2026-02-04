<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KanbanColumn extends Model
{
    protected $fillable = ['event_id', 'name', 'order', 'color_hex'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tasks()
    {
        return $this->hasMany(KanbanTask::class, 'column_id')->orderBy('order');
    }
}
