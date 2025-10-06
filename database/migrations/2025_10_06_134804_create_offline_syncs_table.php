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
        Schema::create('offline_syncs', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->string('model_id');
            $table->string('action'); // create, update, delete
            $table->json('data');
            $table->string('client_id'); // unique identifier from client
            $table->timestamp('synced_at')->nullable();
            $table->boolean('is_synced')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index(['model_type', 'model_id']);
            $table->index(['client_id']);
            $table->index(['is_synced']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offline_syncs');
    }
};
