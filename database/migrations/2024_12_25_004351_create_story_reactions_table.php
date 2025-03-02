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
        Schema::create('story_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con el usuario
            $table->foreignId('story_id')->constrained()->onDelete('cascade'); // Relación con la historia
            $table->string('reaction_type'); // Tipo de reacción: like, love, surprise, etc.
            
            $table->timestamps();
        
            $table->unique(['user_id', 'story_id']); // Un usuario solo puede reaccionar una vez por historia
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('story_reactions', function (Blueprint $table) {
            //
        });
    }
};
