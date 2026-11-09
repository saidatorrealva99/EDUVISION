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
    Schema::create('exportaciones_siagie', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
        $table->foreignId('seccion_id')->constrained('secciones')->restrictOnDelete();
        $table->foreignId('periodo_id')->constrained('periodos')->restrictOnDelete();
        $table->string('archivo');
        $table->boolean('incompleta')->default(false)->comment('1 si el director confirmó con notas faltantes');
        $table->timestamp('created_at')->nullable();
    });
}

public function down(): void
{
    Schema::dropIfExists('exportaciones_siagie');
}
};
