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
    Schema::create('citaciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('matricula_id')->constrained('matriculas')->restrictOnDelete();
        $table->foreignId('caso_id')->nullable()->constrained('casos')->restrictOnDelete();
        $table->foreignId('citado_por')->constrained('users')->restrictOnDelete();
        $table->dateTime('fecha_hora')->comment('Futura al crearla (se valida en Laravel)');
        $table->string('motivo', 255);
        $table->enum('estado', ['enviada', 'confirmada', 'reprogramar', 'realizada', 'no_asistio'])->default('enviada');
        $table->string('respuesta', 255)->nullable();
        $table->timestamps();

        $table->index('fecha_hora');
    });

    DB::statement("ALTER TABLE citaciones ADD CONSTRAINT chk_cit_motivo CHECK (CHAR_LENGTH(TRIM(motivo)) >= 5)");
}

public function down(): void
{
    Schema::dropIfExists('citaciones');
}
};
