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
        Schema::create('landing_page_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identifier')->unique(); // ex: tema-inicial
            $table->json('config_schema'); // Definição dos campos (text, image, array, etc)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_templates');
    }
};
