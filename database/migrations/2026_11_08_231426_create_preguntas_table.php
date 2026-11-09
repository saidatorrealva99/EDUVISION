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
    Schema::create('preguntas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('encuesta_id')->constrained('encuestas')->restrictOnDelete();
        $table->string('texto', 255);
        $table->enum('tipo', ['escala', 'abierta']);
        $table->unsignedTinyInteger('orden');

        $table->unique(['encuesta_id', 'orden']);
    });
}

public function down(): void
{
    Schema::dropIfExists('preguntas');
}
};
