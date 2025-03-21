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
        Schema::create('reservas_usuarios', function (Blueprint $table) {
            $table->id();
            $table->integer('orden');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('reserva_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'reserva_id'], 'reservas_usuarios_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas_usuarios');
    }
};
