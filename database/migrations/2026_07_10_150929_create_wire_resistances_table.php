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
        Schema::create('wire_resistances', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('section_1')->nullable();
            $table->string('section_2')->nullable();
            $table->string('section_3')->nullable();
            $table->string('section_4')->nullable();
            $table->string('section_5')->nullable();
            $table->string('section_6')->nullable();
            $table->string('section_7')->nullable();
            $table->string('section_8')->nullable();
            $table->string('section_9')->nullable();
            $table->string('section_10')->nullable();
            $table->string('resistance')->nullable();
            $table->integer('columns_count')->nullable();
            $table->foreignId('protocol_id')->nullable()->constrained()->cascadeOnDelete();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wire_resistances');
    }
};
