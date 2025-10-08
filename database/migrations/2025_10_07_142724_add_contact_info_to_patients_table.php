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
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('patients', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('age');
            }
            if (!Schema::hasColumn('patients', 'permanent_address')) {
                $table->text('permanent_address')->nullable()->after('age');
            }
            if (!Schema::hasColumn('patients', 'current_address')) {
                $table->text('current_address')->nullable()->after('age');
            }
            if (!Schema::hasColumn('patients', 'occupation')) {
                $table->string('occupation')->nullable()->after('age');
            }
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
