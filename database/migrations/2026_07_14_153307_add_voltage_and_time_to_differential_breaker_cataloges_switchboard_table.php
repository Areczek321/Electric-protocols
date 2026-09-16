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
        Schema::table('differential_breaker_cataloges_switchboard', function (Blueprint $table) {
            $table->decimal('time',6,2)->nullable();
            $table->decimal('safe_voltage',6,2)->nullable();
            $table->decimal('current',6,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('differential_breaker_cataloges_switchboard', function (Blueprint $table) {
            $table->dropColumn('time');
            $table->dropColumn('safe_voltage');
            $table->dropColumn('current');
        });
    }
};
