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
    Schema::create('mapeo_siagie', function (Blueprint $table) {
        $table->id();
        $table->foreignId('anio_escolar_id')->constrained('anios_escolares')->restrictOnDelete();
        $table->foreignId('competencia_id')->constrained('competencias')->restrictOnDelete();
        $table->string('columna', 3)->comment('Columna de la plantilla, p. ej. F');

        $table->unique(['anio_escolar_id', 'competencia_id']);
    });

    DB::statement("ALTER TABLE mapeo_siagie ADD CONSTRAINT chk_mapeo_columna CHECK (columna REGEXP '^[A-Z]{1,3}$')");
}

public function down(): void
{
    Schema::dropIfExists('mapeo_siagie');
}
};
