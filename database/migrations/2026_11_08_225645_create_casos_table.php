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
    Schema::create('casos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('matricula_id')->constrained('matriculas')->restrictOnDelete();
        $table->foreignId('responsable_id')->constrained('users')->restrictOnDelete();
        $table->enum('origen', ['alerta', 'manual']);
        $table->enum('prioridad', ['baja', 'media', 'alta'])->default('media');
        $table->enum('estado', ['abierto', 'en_intervencion', 'en_seguimiento', 'cerrado', 'reabierto'])->default('abierto');
        $table->enum('resultado', ['mejoro', 'sin_cambio', 'derivado'])->nullable();
        $table->dateTime('abierto_at');
        $table->dateTime('cerrado_at')->nullable();
        $table->timestamps();

        $table->index('estado');
        $table->index(['responsable_id', 'estado']);
    });

    // RN-10: un caso cerrado tiene resultado y fecha de cierre; uno no cerrado no tiene fecha de cierre
    DB::statement("ALTER TABLE casos ADD CONSTRAINT chk_casos_cierre CHECK (
        (estado = 'cerrado' AND resultado IS NOT NULL AND cerrado_at IS NOT NULL)
        OR (estado <> 'cerrado' AND cerrado_at IS NULL)
    )");
    DB::statement("ALTER TABLE casos ADD CONSTRAINT chk_casos_fechas CHECK (cerrado_at IS NULL OR cerrado_at >= abierto_at)");
}

public function down(): void
{
    Schema::dropIfExists('casos');
}
};
