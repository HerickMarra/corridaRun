<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AiNode extends Model
{
    protected $fillable = ['type', 'label', 'content', 'x', 'y'];

    /**
     * Nodes that this node points to.
     */
    public function outgoingConnections(): BelongsToMany
    {
        return $this->belongsToMany(AiNode::class, 'ai_connections', 'from_node_id', 'to_node_id')
            ->withTimestamps();
    }

    /**
     * Nodes that point to this node.
     */
    public function incomingConnections(): BelongsToMany
    {
        return $this->belongsToMany(AiNode::class, 'ai_connections', 'to_node_id', 'from_node_id')
            ->withTimestamps();
    }
}
