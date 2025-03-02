<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventoSanValentinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evento_san_valentin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID de usuario
            $table->string('nombre_pareja');
            $table->string('carrera');
            $table->string('semestre');
            $table->boolean('anonimato')->default(true); // Campo para anonimato
            $table->timestamps();

            // Establecer las relaciones
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evento_san_valentin');
    }
}
