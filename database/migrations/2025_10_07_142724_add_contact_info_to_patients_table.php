<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('age');
            $table->text('permanent_address')->nullable()->after('phone_number');
            $table->text('current_address')->nullable()->after('permanent_address');
            $table->string('occupation')->nullable()->after('current_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'permanent_address', 'current_address', 'occupation']);
        });
    }
};
