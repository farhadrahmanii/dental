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
            $table->foreignId("patient_id")
                ->constrained()
                ->onDelete('cascade');
             $table->enum('treatment_type', array_column(DentalTreatment::cases(), 'value'));
             $table->string('treatment_description')->nullable();
             $table->date('treatment_date');
             $table->enum('tooth_number', ToothNumber::values());
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
