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
    Schema::create('evaluaciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('asignacion_id')->constrained('asignaciones')->restrictOnDelete();
        $table->foreignId('competencia_id')->constrained('competencias')->restrictOnDelete();
        $table->foreignId('periodo_id')->constrained('periodos')->restrictOnDelete();
        $table->string('nombre', 100);
        $table->enum('tipo', ['examen', 'practica', 'proyecto', 'participacion', 'tarea']);
        $table->decimal('peso', 5, 2)->comment('Suman 100 por competencia y periodo (RN-06, se valida en Laravel)');
        $table->date('fecha')->nullable();
        $table->timestamps();
        $table->softDeletes();

        $table->index(['asignacion_id', 'periodo_id']);
    });

    DB::statement("ALTER TABLE evaluaciones ADD CONSTRAINT chk_eval_peso CHECK (peso > 0 AND peso <= 100)");
}

public function down(): void
{
    Schema::dropIfExists('evaluaciones');
}
};
