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
    Schema::create('escala_valores', function (Blueprint $table) {
        $table->id();
        $table->foreignId('escala_id')->constrained('escalas')->cascadeOnDelete();
        $table->string('codigo', 3);
        $table->string('descripcion', 50)->nullable();
        $table->decimal('minimo', 4, 2);
        $table->decimal('maximo', 4, 2);
        $table->unsignedTinyInteger('orden');

        $table->unique(['escala_id', 'codigo']);
        $table->unique(['escala_id', 'orden']);
    });

    DB::statement("ALTER TABLE escala_valores ADD CONSTRAINT chk_escval_rango CHECK (minimo >= 0 AND maximo <= 20 AND minimo <= maximo)");
}

public function down(): void
{
    Schema::dropIfExists('escala_valores');
}
};
