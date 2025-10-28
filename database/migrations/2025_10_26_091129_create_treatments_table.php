<?php

use App\Enums\DentalTreatment;
use App\Enums\ToothNumber;
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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            // Foreign key to patients table
            $table->foreignId('patient_id')
                  ->constrained(table: 'patients', column: 'register_id')
                  ->onDelete('cascade');
             $table->foreignId('service_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('treatment_description')->nullable();
            $table->date('treatment_date');
            $table->json('tooth_numbers');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
