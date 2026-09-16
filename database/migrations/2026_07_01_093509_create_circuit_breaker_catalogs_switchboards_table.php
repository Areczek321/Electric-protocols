<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('circuit_breaker_catalog_switchboard', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('circuit_breaker_catalog_id')->nullable()
                ->constrained(
                    table: 'circuit_breaker_catalogs',
                    indexName: 'fk_cbc_id'
                )
                ->cascadeOnDelete();

            $table->foreignId('switchboard_id')->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('slot_number')->nullable();
            $table->string('slots_occupation')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circuit_breaker_catalog_switchboard');
    }
};
