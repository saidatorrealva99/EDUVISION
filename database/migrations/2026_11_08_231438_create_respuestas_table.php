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
    Schema::create('respuestas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('encuesta_id')->constrained('encuestas')->restrictOnDelete();
        $table->foreignId('asignacion_id')->constrained('asignaciones')->restrictOnDelete();
        $table->foreignId('pregunta_id')->constrained('preguntas')->restrictOnDelete();
        $table->unsignedTinyInteger('valor')->nullable()->comment('1 a 5 en preguntas de escala');
        $table->text('texto')->nullable()->comment('Respuesta abierta');
        $table->boolean('visible')->default(false)->comment('Texto abierto aprobado por moderación (RN-08)');
        $table->date('fecha')->comment('Sin hora, para impedir cruzar datos');

        $table->index(['asignacion_id', 'pregunta_id']);
        $table->comment('SIN estudiante_id: anonimato por diseño');
    });

    DB::statement("ALTER TABLE respuestas ADD CONSTRAINT chk_resp_valor CHECK (valor IS NULL OR valor BETWEEN 1 AND 5)");
    DB::statement("ALTER TABLE respuestas ADD CONSTRAINT chk_resp_contenido CHECK (valor IS NOT NULL OR texto IS NOT NULL)");
}

public function down(): void
{
    Schema::dropIfExists('respuestas');
}
};
