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
    Schema::create('asistencias', function (Blueprint $table) {
        $table->id();
        $table->foreignId('matricula_id')->constrained('matriculas')->restrictOnDelete();
        $table->foreignId('asignacion_id')->constrained('asignaciones')->restrictOnDelete();
        $table->date('fecha')->comment('No futura (se valida en Laravel)');
        $table->enum('estado', ['P', 'T', 'F', 'FJ'])->comment('Presente, tardanza, falta, falta justificada');
        $table->enum('metodo', ['manual', 'qr'])->default('manual');
        $table->foreignId('registrado_por')->constrained('users')->restrictOnDelete();
        $table->timestamps();

        $table->unique(['matricula_id', 'asignacion_id', 'fecha']);
        $table->index('fecha');
        $table->index(['matricula_id', 'estado', 'fecha']);
    });
}

public function down(): void
{
    Schema::dropIfExists('asistencias');
}
};
