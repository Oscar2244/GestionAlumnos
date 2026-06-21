<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla ALUMNOS
     * Cumple 3FN: id_curso es clave foránea hacia cursos (no se repite
     * el nombre del curso ni el turno dentro de esta tabla).
     * Relación: un alumno pertenece a UN curso (belongsTo),
     *           un curso puede tener MUCHOS alumnos (hasMany).
     */
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_alumno', 150);
            $table->string('email', 150)->unique();
            $table->unsignedBigInteger('id_curso');
            $table->decimal('nota', 4, 2)->nullable(); // campo extra pedido en la rúbrica
            $table->timestamps();

            $table->foreign('id_curso')
                  ->references('id')->on('cursos')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
