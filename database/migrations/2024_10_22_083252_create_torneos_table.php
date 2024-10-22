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
        Schema::create('jugadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('nivel_habilidad');
            $table->enum('genero', ['masculino', 'femenino']);
            $table->integer('fuerza')->nullable();
            $table->integer('velocidad')->nullable();
            $table->integer('tiempo_reaccion')->nullable();
            $table->timestamps();
        });

        Schema::create('torneos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->enum('genero', ['masculino', 'femenino']);
            $table->unsignedBigInteger('ganador_id')->nullable();
            $table->timestamps();
            
            $table->foreign('ganador_id')->references('id')->on('jugadores');
        });

        Schema::create('torneo_participantes', function (Blueprint $table) {
            $table->unsignedBigInteger('torneo_id');
            $table->unsignedBigInteger('jugador_id');
            $table->primary(['torneo_id', 'jugador_id']);
            $table->timestamps();

            $table->foreign('torneo_id')->references('id')->on('torneos');
            $table->foreign('jugador_id')->references('id')->on('jugadores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('torneos');
        Schema::dropIfExists('torneo_participantes');
        Schema::dropIfExists('jugadores');
    }
};
