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
        Schema::create('insulation_catalogs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('insulation')->nullable();
            $table->decimal('resistance_allowed',8,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insulation_catalogs');
    }
};
