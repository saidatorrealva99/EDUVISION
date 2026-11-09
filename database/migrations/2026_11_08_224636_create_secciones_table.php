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
    Schema::create('secciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('grado_id')->constrained('grados')->restrictOnDelete();
        $table->foreignId('anio_escolar_id')->constrained('anios_escolares')->restrictOnDelete();
        $table->char('letra', 1);
        $table->unsignedTinyInteger('capacidad')->default(30);
        $table->foreignId('tutor_id')->nullable()->constrained('docentes')->nullOnDelete();
        $table->timestamps();

        $table->unique(['grado_id', 'anio_escolar_id', 'letra']);
        $table->unique(['id', 'anio_escolar_id'], 'secciones_id_anio');
    });

    DB::statement("ALTER TABLE secciones ADD CONSTRAINT chk_secciones_letra CHECK (letra REGEXP '^[A-Z]$')");
    DB::statement("ALTER TABLE secciones ADD CONSTRAINT chk_secciones_capacidad CHECK (capacidad BETWEEN 1 AND 45)");
}

public function down(): void
{
    Schema::dropIfExists('secciones');
}
};
