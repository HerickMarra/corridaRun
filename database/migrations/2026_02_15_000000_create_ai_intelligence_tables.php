<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // emotion, personality, instruction, context
            $table->string('label');
            $table->text('content')->nullable();
            $table->decimal('x', 10, 2)->default(0);
            $table->decimal('y', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('ai_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_node_id')->constrained('ai_nodes')->onDelete('cascade');
            $table->foreignId('to_node_id')->constrained('ai_nodes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_connections');
        Schema::dropIfExists('ai_nodes');
    }
};
