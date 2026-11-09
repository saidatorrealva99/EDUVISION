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
    Schema::create('eventos_calendario', function (Blueprint $table) {
        $table->id();
        $table->string('titulo', 150);
        $table->enum('tipo', ['examen', 'evento', 'reunion', 'feriado']);
        $table->dateTime('inicio');
        $table->dateTime('fin')->nullable();
        $table->enum('alcance_tipo', ['colegio', 'nivel', 'seccion']);
        $table->unsignedBigInteger('alcance_id')->nullable();
        $table->timestamps();

        $table->index('inicio');
    });

    DB::statement("ALTER TABLE eventos_calendario ADD CONSTRAINT chk_evt_fechas CHECK (fin IS NULL OR fin >= inicio)");
    DB::statement("ALTER TABLE eventos_calendario ADD CONSTRAINT chk_evt_alcance CHECK (
        (alcance_tipo = 'colegio' AND alcance_id IS NULL) OR (alcance_tipo <> 'colegio' AND alcance_id IS NOT NULL)
    )");
}

public function down(): void
{
    Schema::dropIfExists('eventos_calendario');
}
};
