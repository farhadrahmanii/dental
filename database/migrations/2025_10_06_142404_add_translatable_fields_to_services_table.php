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
        Schema::table('services', function (Blueprint $table) {
            // Add translatable fields for service name
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_ps')->nullable()->after('name_en');
            $table->string('name_fa')->nullable()->after('name_ps');
            
            // Add translatable fields for service description
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_ps')->nullable()->after('description_en');
            $table->text('description_fa')->nullable()->after('description_ps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'name_en', 'name_ps', 'name_fa',
                'description_en', 'description_ps', 'description_fa'
            ]);
        });
    }
};
