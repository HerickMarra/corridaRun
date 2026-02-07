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
        Schema::table('email_templates', function (Blueprint $table) {
            $table->boolean('is_system')->default(false)->after('is_active');
        });

        // Marcar templates existentes como sistema
        DB::table('email_templates')->whereIn('slug', ['welcome', 'registration', 'password-reset'])->update(['is_system' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });
    }
};
