<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiConnection extends Model
{
    protected $fillable = ['from_node_id', 'to_node_id'];

    public function fromNode(): BelongsTo
    {
        return $this->belongsTo(AiNode::class, 'from_node_id');
    }

    public function toNode(): BelongsTo
    {
        return $this->belongsTo(AiNode::class, 'to_node_id');
    }
}
