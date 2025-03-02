<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('carrera')->nullable()->after('bio'); // Agregar la columna en profiles
            $table->string('nombre_perfil')->nullable()->after('privacy'); // Agregar la columna en profiles
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'carrera')) {
                $table->dropColumn('carrera');
                $table->dropColumn('nombre_perfil'); 
            }
        });
    }
};
