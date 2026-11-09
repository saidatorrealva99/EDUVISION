<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docentes')->restrictOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->restrictOnDelete();
            $table->unsignedBigInteger('seccion_id');
            $table->foreignId('anio_escolar_id')->constrained('anios_escolares')->restrictOnDelete();
            $table->timestamps();

            // RN-03: un solo docente por curso y sección en cada año
            $table->unique(['curso_id', 'seccion_id', 'anio_escolar_id']);
            $table->index(['docente_id', 'anio_escolar_id']);

            $table->foreign(['seccion_id', 'anio_escolar_id'], 'fk_asig_seccion_anio')
                  ->references(['id', 'anio_escolar_id'])->on('secciones')
                  ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};