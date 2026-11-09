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
    Schema::create('reglas_riesgo', function (Blueprint $table) {
        $table->id();
        $table->string('codigo', 5)->unique()->comment('R1..R7');
        $table->string('nombre', 100);
        $table->json('parametros')->comment('Umbrales configurables, p. ej. {"faltas":3,"dias_habiles":10}');
        $table->unsignedTinyInteger('puntos');
        $table->boolean('activa')->default(true);
        $table->timestamps();
    });

    DB::statement("ALTER TABLE reglas_riesgo ADD CONSTRAINT chk_reglas_codigo CHECK (codigo REGEXP '^R[0-9]{1,2}$')");
    DB::statement("ALTER TABLE reglas_riesgo ADD CONSTRAINT chk_reglas_puntos CHECK (puntos BETWEEN 1 AND 10)");
}

public function down(): void
{
    Schema::dropIfExists('reglas_riesgo');
}
};
