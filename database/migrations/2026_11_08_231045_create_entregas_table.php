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
    Schema::create('entregas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tarea_id')->constrained('tareas')->restrictOnDelete();
        $table->foreignId('matricula_id')->constrained('matriculas')->restrictOnDelete();
        $table->string('archivo')->nullable();
        $table->text('texto')->nullable();
        $table->dateTime('entregado_at');
        $table->boolean('fuera_de_plazo')->default(false);
        $table->enum('calificacion', ['AD', 'A', 'B', 'C'])->nullable();
        $table->timestamps();

        $table->unique(['tarea_id', 'matricula_id']);
    });

    DB::statement("ALTER TABLE entregas ADD CONSTRAINT chk_entregas_contenido CHECK (archivo IS NOT NULL OR texto IS NOT NULL)");
}

public function down(): void
{
    Schema::dropIfExists('entregas');
}
};
