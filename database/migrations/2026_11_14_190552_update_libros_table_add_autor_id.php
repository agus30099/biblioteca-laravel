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
        Schema::table('libros', function (Blueprint $table) {
            // Agregamos la relación con autor y quitamos el campo autor de texto
            $table->foreignId('autor_id')->nullable()->constrained('autors')->onDelete('set null');
            $table->dropColumn('autor'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
