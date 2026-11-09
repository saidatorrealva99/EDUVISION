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
    Schema::create('tareas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('asignacion_id')->constrained('asignaciones')->restrictOnDelete();
        $table->foreignId('evaluacion_id')->nullable()->constrained('evaluaciones')->nullOnDelete()
              ->comment('Opcional: si la tarea se califica');
        $table->string('titulo', 150);
        $table->text('descripcion')->nullable();
        $table->dateTime('fecha_entrega');
        $table->boolean('avisar_familias')->default(true)->comment('RF-61');
        $table->dateTime('publicado_at')->nullable();
        $table->timestamps();
        $table->softDeletes();

        $table->index(['asignacion_id', 'fecha_entrega']);
    });

    DB::statement("ALTER TABLE tareas ADD CONSTRAINT chk_tareas_titulo CHECK (CHAR_LENGTH(TRIM(titulo)) >= 3)");
    DB::statement("ALTER TABLE tareas ADD CONSTRAINT chk_tareas_fechas CHECK (publicado_at IS NULL OR fecha_entrega > publicado_at)");
}

public function down(): void
{
    Schema::dropIfExists('tareas');
}
};
