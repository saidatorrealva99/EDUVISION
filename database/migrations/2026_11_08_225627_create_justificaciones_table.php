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
    Schema::create('justificaciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('asistencia_id')->constrained('asistencias')->restrictOnDelete();
        $table->foreignId('apoderado_id')->constrained('apoderados')->restrictOnDelete();
        $table->string('motivo', 255);
        $table->string('archivo')->nullable();
        $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente')->comment('RN-13');
        $table->foreignId('revisado_por')->nullable()->constrained('users')->restrictOnDelete();
        $table->dateTime('revisado_at')->nullable();
        $table->timestamps();

        $table->index('estado');
    });

    DB::statement("ALTER TABLE justificaciones ADD CONSTRAINT chk_just_motivo CHECK (CHAR_LENGTH(TRIM(motivo)) >= 5)");
    DB::statement("ALTER TABLE justificaciones ADD CONSTRAINT chk_just_revision CHECK (estado = 'pendiente' OR revisado_at IS NOT NULL)");
}

public function down(): void
{
    Schema::dropIfExists('justificaciones');
}
};
