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
        Schema::create('circuit_breaker_catalog_room', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('circuit_breaker_catalog_id')
                ->nullable()
                ->constrained('circuit_breaker_catalogs')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
                ['room_id', 'circuit_breaker_catalog_id'],
                'room_breaker_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circuit_breaker_catalog_room');
    }
};
