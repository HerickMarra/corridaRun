<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KanbanTask extends Model
{
    protected $fillable = [
        'column_id',
        'event_id',
        'user_id',
        'assigned_to',
        'title',
        'description',
        'priority',
        'due_date',
        'order'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function column()
    {
        return $this->belongsTo(KanbanColumn::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
