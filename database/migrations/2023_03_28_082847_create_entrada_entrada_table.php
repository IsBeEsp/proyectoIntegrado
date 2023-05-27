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
        Schema::create('entrada_entrada', function (Blueprint $table) {
            $table->id();
            $table->integer('entradaPadre')->foreignId('entrada_id')->constrained()->onDelete('cascade');
            $table->integer('entradaHija')->foreignId('entrada_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrada_entrada');
    }
};
