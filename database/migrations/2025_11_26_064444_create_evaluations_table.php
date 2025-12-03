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
        Schema::create('evaluations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('alternative_id')->constrained()->cascadeOnDelete();
        $table->foreignId('criteria_id')->constrained()->cascadeOnDelete();
        $table->float('value'); // Nilai spesifikasi (bisa angka asli atau skala 1-5)
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
