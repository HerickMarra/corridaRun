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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('allow_user_refund')->default(true)->after('status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->timestamp('refunded_at')->nullable()->after('updated_at');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('refunded_at');
            $table->string('refund_status')->nullable()->after('refund_amount'); // requested, processed, failed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('allow_user_refund');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['refunded_at', 'refund_amount', 'refund_status']);
        });
    }
};
