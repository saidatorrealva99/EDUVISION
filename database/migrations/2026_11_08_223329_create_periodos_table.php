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
    Schema::create('periodos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('anio_escolar_id')->constrained('anios_escolares')->restrictOnDelete();
        $table->string('nombre', 30);
        $table->unsignedTinyInteger('orden');
        $table->date('fecha_inicio');
        $table->date('fecha_fin');
        $table->enum('estado', ['planificado', 'abierto', 'cerrado', 'en_correccion'])->default('planificado');
        $table->tinyInteger('abierto_unico')->nullable()->storedAs("IF(estado = 'abierto', 1, NULL)");
        $table->timestamps();

        $table->unique(['anio_escolar_id', 'orden']);
        $table->unique(['anio_escolar_id', 'nombre']);
        $table->unique(['anio_escolar_id', 'abierto_unico'], 'periodos_un_solo_abierto');
    });

    DB::statement("ALTER TABLE periodos ADD CONSTRAINT chk_periodos_orden CHECK (orden BETWEEN 1 AND 6)");
    DB::statement("ALTER TABLE periodos ADD CONSTRAINT chk_periodos_fechas CHECK (fecha_fin > fecha_inicio)");
}

public function down(): void
{
    Schema::dropIfExists('periodos');
}
};
