<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiNode;
use App\Models\AiConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiIntelligenceController extends Controller
{
    /**
     * Display the visual builder.
     */
    public function index()
    {
        $nodes = AiNode::all();
        $connections = AiConnection::all();

        return view('admin.ai.visual-builder', compact('nodes', 'connections'));
    }

    /**
     * Save the entire graph.
     */
    public function save(Request $request)
    {
        $data = $request->validate([
            'nodes' => 'present|array',
            'connections' => 'present|array',
        ]);

        DB::beginTransaction();
        try {
            // Clear existing data (simplified approach for MVP)
            AiConnection::truncate();
            AiNode::truncate();

            $nodeMapping = [];

            // Re-create nodes
            foreach ($data['nodes'] as $nodeData) {
                $node = AiNode::create([
                    'type' => $nodeData['type'],
                    'label' => $nodeData['label'] ?? 'Node',
                    'content' => $nodeData['content'] ?? '',
                    'x' => $nodeData['x'] ?? 0,
                    'y' => $nodeData['y'] ?? 0,
                ]);
                $nodeMapping[$nodeData['id']] = $node->id;
            }

            // Re-create connections
            foreach ($data['connections'] as $connData) {
                AiConnection::create([
                    'from_node_id' => $nodeMapping[$connData['from']],
                    'to_node_id' => $nodeMapping[$connData['to']],
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
