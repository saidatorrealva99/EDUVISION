<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->restrictOnDelete();
            $table->foreignId('creado_por')->constrained('users')->restrictOnDelete();
            $table->string('descripcion', 255);
            $table->enum('estado', ['pendiente', 'en_progreso', 'alcanzada'])->default('pendiente');
            $table->date('fecha_objetivo')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE metas ADD CONSTRAINT chk_metas_desc CHECK (CHAR_LENGTH(TRIM(descripcion)) >= 5)");
    }

    public function down(): void
    {
        Schema::dropIfExists('metas');
    }
};