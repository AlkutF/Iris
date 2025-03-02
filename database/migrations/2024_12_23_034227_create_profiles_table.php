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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Columna para la relación con el usuario
            $table->text('bio')->nullable();
            $table->string('privacy');
            $table->string('gender')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
    
            // Establecer la relación con la tabla de usuarios
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
};
