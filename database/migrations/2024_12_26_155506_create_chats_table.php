<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id(); // Columna ID única del chat
            $table->string('name'); // Nombre del chat
            $table->enum('type', ['public', 'private']); // Tipo de chat: público o privado
            $table->timestamps(); // Para las fechas de creación y actualización
        });

        // Crear la tabla de relación entre chats y usuarios
        Schema::create('chat_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->onDelete('cascade'); // Relación con la tabla chats
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con la tabla users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar las tablas en reversa
        Schema::dropIfExists('chat_user');
        Schema::dropIfExists('chats');
    }
};
