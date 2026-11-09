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
    Schema::create('anios_escolares', function (Blueprint $table) {
        $table->id();
        $table->foreignId('institucion_id')->constrained('instituciones')->restrictOnDelete();
        $table->unsignedSmallInteger('anio');
        $table->date('fecha_inicio');
        $table->date('fecha_fin');
        $table->enum('estado', ['planificado', 'activo', 'cerrado'])->default('planificado');
        $table->tinyInteger('activo_unico')->nullable()->storedAs("IF(estado = 'activo', 1, NULL)");
        $table->timestamps();

        $table->unique(['institucion_id', 'anio']);
        $table->unique(['institucion_id', 'activo_unico'], 'anios_un_solo_activo');
    });

    DB::statement("ALTER TABLE anios_escolares ADD CONSTRAINT chk_anios_anio CHECK (anio BETWEEN 2000 AND 2100)");
    DB::statement("ALTER TABLE anios_escolares ADD CONSTRAINT chk_anios_fechas CHECK (fecha_fin > fecha_inicio)");
    DB::statement("ALTER TABLE anios_escolares ADD CONSTRAINT chk_anios_mismo_anio CHECK (YEAR(fecha_inicio) = anio)");
}

public function down(): void
{
    Schema::dropIfExists('anios_escolares');
}
};
