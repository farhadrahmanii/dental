<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id('register_id');
            $table->string('x_ray_id')->nullable();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->enum('sex', ['male', 'female', 'other'])->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            // diagnosis stores URL or base64-saved URL from CanvasPointerField
            $table->string('diagnosis')->nullable();
            $table->longText('comment')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            // store multiple image paths as JSON array
            $table->json('images')->nullable();
            $table->text('treatment')->nullable();
            $table->string('doctor_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};


