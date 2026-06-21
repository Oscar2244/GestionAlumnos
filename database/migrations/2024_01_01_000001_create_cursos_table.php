<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla CURSOS
     * Cumple 3FN: cada columna depende únicamente de la clave primaria (id).
     * No hay datos repetidos ni dependencias transitivas.
     */
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_curso', 100);
            $table->enum('turno', ['mañana', 'tarde', 'noche']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
