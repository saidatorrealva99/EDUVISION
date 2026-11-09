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
    Schema::create('preferencias_notificacion', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->enum('tipo', ['tarea', 'falta', 'alerta', 'comunicado', 'citacion', 'resumen']);
        $table->enum('canal', ['database', 'push', 'mail']);
        $table->boolean('activo')->default(true);
        $table->timestamps();

        $table->unique(['user_id', 'tipo', 'canal']);
    });

    // RF-62: los avisos de faltas y citaciones no se pueden desactivar
    DB::statement("ALTER TABLE preferencias_notificacion ADD CONSTRAINT chk_pref_obligatorios CHECK (tipo NOT IN ('falta', 'citacion') OR activo = 1)");
}

public function down(): void
{
    Schema::dropIfExists('preferencias_notificacion');
}
};
