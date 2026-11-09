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
    Schema::create('apoderado_estudiante', function (Blueprint $table) {
        $table->id();
        $table->foreignId('apoderado_id')->constrained('apoderados')->restrictOnDelete();
        $table->foreignId('estudiante_id')->constrained('estudiantes')->restrictOnDelete();
        $table->enum('parentesco', ['madre', 'padre', 'apoderado', 'otro']);
        $table->boolean('es_principal')->default(false);
        $table->boolean('activo')->default(true)->comment('Sin vínculo activo no hay acceso (RN-09)');
        $table->tinyInteger('principal_unico')->nullable()
              ->storedAs('IF(es_principal = 1 AND activo = 1, 1, NULL)');
        $table->timestamps();

        $table->unique(['apoderado_id', 'estudiante_id']);
        $table->unique(['estudiante_id', 'principal_unico'], 'apoest_un_principal');
    });
}

public function down(): void
{
    Schema::dropIfExists('apoderado_estudiante');
}
};
