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
    Schema::create('notas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('evaluacion_id')->constrained('evaluaciones')->restrictOnDelete();
        $table->foreignId('matricula_id')->constrained('matriculas')->restrictOnDelete();
        $table->enum('valor_literal', ['AD', 'A', 'B', 'C'])->nullable()->comment('Escala literal');
        $table->decimal('valor_numerico', 4, 2)->nullable()->comment('Escala numérica de 0 a 20');
        $table->foreignId('registrado_por')->constrained('users')->restrictOnDelete();
        $table->timestamps();
        $table->softDeletes();

        $table->unique(['evaluacion_id', 'matricula_id']);
    });

    // Exactamente uno de los dos valores: literal o numérico, nunca ambos ni ninguno
    DB::statement("ALTER TABLE notas ADD CONSTRAINT chk_notas_un_valor CHECK ((valor_literal IS NULL) <> (valor_numerico IS NULL))");
    DB::statement("ALTER TABLE notas ADD CONSTRAINT chk_notas_rango CHECK (valor_numerico IS NULL OR valor_numerico BETWEEN 0 AND 20)");
}

public function down(): void
{
    Schema::dropIfExists('notas');
}
};
