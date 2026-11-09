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
    Schema::create('riesgo_snapshots', function (Blueprint $table) {
        $table->id();
        $table->foreignId('matricula_id')->constrained('matriculas')->restrictOnDelete();
        $table->date('fecha');
        $table->unsignedTinyInteger('puntaje');
        $table->enum('nivel', ['verde', 'ambar', 'rojo']);
        $table->json('motivos')->comment('Reglas activadas y datos de origen (RF-41)');
        $table->timestamp('created_at')->nullable();

        $table->unique(['matricula_id', 'fecha']);
        $table->index(['fecha', 'nivel']);
    });

    DB::statement("ALTER TABLE riesgo_snapshots ADD CONSTRAINT chk_riesgo_puntaje CHECK (puntaje <= 60)");
}

public function down(): void
{
    Schema::dropIfExists('riesgo_snapshots');
}
};
