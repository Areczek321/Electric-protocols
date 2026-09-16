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
        Schema::table('wire_resistances', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wire_resistances', function (Blueprint $table) {
            $table->dropColumn('room_id');
            $table->string('name')->nullable(false)->change();
        });
    }
};
