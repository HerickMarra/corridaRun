<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_event_tag', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('event_id')->constrained()->cascadeOnDelete();
            $blueprint->foreignId('event_tag_id')->constrained()->cascadeOnDelete();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_event_tag');
    }
};
