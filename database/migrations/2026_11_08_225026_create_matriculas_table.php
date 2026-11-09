<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->restrictOnDelete();
            $table->unsignedBigInteger('seccion_id');
            $table->foreignId('anio_escolar_id')->constrained('anios_escolares')->restrictOnDelete();
            $table->date('fecha');
            $table->enum('estado', ['activa', 'trasladado', 'retirado'])->default('activa');
            $table->timestamps();
            $table->softDeletes();

            // RN-01: un estudiante tiene una sola matrícula por año
            $table->unique(['estudiante_id', 'anio_escolar_id']);
            $table->index(['seccion_id', 'estado']);

            // Clave foránea doble: la sección debe ser del MISMO año de la matrícula
            $table->foreign(['seccion_id', 'anio_escolar_id'], 'fk_matriculas_seccion_anio')
                  ->references(['id', 'anio_escolar_id'])->on('secciones')
                  ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};