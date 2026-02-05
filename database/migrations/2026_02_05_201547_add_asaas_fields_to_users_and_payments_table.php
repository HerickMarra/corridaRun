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
        Schema::table('users', function (Blueprint $table) {
            $table->string('asaas_customer_id')->nullable()->after('email');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('asaas_payment_id')->nullable()->after('payment_method');
            $table->string('invoice_url')->nullable()->after('asaas_payment_id');
            $table->text('pix_qr_code')->nullable()->after('invoice_url');
            $table->text('pix_qr_code_base64')->nullable()->after('pix_qr_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('asaas_customer_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['asaas_payment_id', 'invoice_url', 'pix_qr_code', 'pix_qr_code_base64']);
        });
    }
};
