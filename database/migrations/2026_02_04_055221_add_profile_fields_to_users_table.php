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
            $table->string('profile_photo')->nullable()->after('email');
            $table->string('team')->nullable()->after('phone');
            $table->string('shirt_size')->nullable()->after('team');
            $table->string('shoe_size')->nullable()->after('shirt_size');
            $table->string('blood_type')->nullable()->after('shoe_size');
            $table->string('emergency_contact_name')->nullable()->after('blood_type');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->text('allergies')->nullable()->after('emergency_contact_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo',
                'team',
                'shirt_size',
                'shoe_size',
                'blood_type',
                'emergency_contact_name',
                'emergency_contact_phone',
                'allergies'
            ]);
        });
    }
};
