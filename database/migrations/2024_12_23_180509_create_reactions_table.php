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
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con el usuario
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Relación con el post
            $table->string('reaction_type'); // Tipo de reacción: like, love, surprise, etc.
            $table->timestamps();
    
            $table->unique(['user_id', 'post_id']); // Un usuario solo puede reaccionar una vez por post
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reactions');
    }
};
