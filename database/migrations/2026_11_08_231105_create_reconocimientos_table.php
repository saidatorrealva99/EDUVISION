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
    Schema::create('reconocimientos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('estudiante_id')->constrained('estudiantes')->restrictOnDelete()
              ->comment('Cruza años: va al estudiante, no a la matrícula');
        $table->foreignId('otorgado_por')->constrained('users')->restrictOnDelete();
        $table->enum('tipo', ['logro', 'mejora', 'asistencia', 'deporte', 'cultura']);
        $table->string('descripcion', 255);
        $table->date('fecha');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('reconocimientos');
}
};
