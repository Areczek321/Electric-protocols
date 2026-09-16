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
        Schema::create('insulation_catalog_switchboard', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('switchboard_id')->constrained()->cascadeOnDelete();  
            $table->foreignId('insulation_catalog_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('section')->nullable();
            $table->decimal('cross_section',8,2)->nullable();
            $table->decimal('resistance', 8, 2)->nullable();
            $table->decimal('temperature', 8, 2)->nullable();
            $table->decimal('length',8,2)->nullable();
            $table->decimal('factor',8,2)->default(1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insulation_catalog_switchboard');
    }
};
