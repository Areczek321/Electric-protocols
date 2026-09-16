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
        Schema::create('differential_breaker_cataloges_switchboard', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
            $table->string('section')->nullable();
            $table->string('type')->nullable();
            $table->foreignId('switchboard_id')->nullable();
            $table->foreignId('differential_breaker_cataloges_id')->nullable();
            $table->decimal('current_measured',6,2)->nullable();
            $table->decimal('time_measured',6,2)->nullable();
            $table->decimal('safe_voltage_measured',6,2)->nullable();
            $table->boolean('grade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('differential_breaker_cataloges_switchboard');
    }
};
