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
    Schema::create('curso_grado', function (Blueprint $table) {
        $table->id();
        $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
        $table->foreignId('grado_id')->constrained('grados')->cascadeOnDelete();
        $table->unsignedTinyInteger('horas_semanales')->nullable();

        $table->unique(['curso_id', 'grado_id']);
    });

    DB::statement("ALTER TABLE curso_grado ADD CONSTRAINT chk_cg_horas CHECK (horas_semanales IS NULL OR horas_semanales BETWEEN 1 AND 10)");
}

public function down(): void
{
    Schema::dropIfExists('curso_grado');
}
};
