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
    Schema::create('participaciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('encuesta_id')->constrained('encuestas')->restrictOnDelete();
        $table->foreignId('estudiante_id')->constrained('estudiantes')->restrictOnDelete();
        $table->foreignId('asignacion_id')->constrained('asignaciones')->restrictOnDelete();

        $table->unique(['encuesta_id', 'estudiante_id', 'asignacion_id']);
        $table->comment('Solo evita respuestas duplicadas. SIN relación con respuestas y SIN timestamps (RN-07)');
    });
}

public function down(): void
{
    Schema::dropIfExists('participaciones');
}
};
