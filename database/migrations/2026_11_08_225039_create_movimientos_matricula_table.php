<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_matricula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->restrictOnDelete();
            $table->enum('tipo', ['traslado', 'retiro', 'cambio_seccion']);
            $table->foreignId('seccion_anterior_id')->nullable()->constrained('secciones')->restrictOnDelete();
            $table->string('motivo', 255);
            $table->date('fecha');
            $table->foreignId('registrado_por')->constrained('users')->restrictOnDelete();
            $table->timestamp('created_at')->nullable();
        });

        DB::statement("ALTER TABLE movimientos_matricula ADD CONSTRAINT chk_mov_motivo CHECK (CHAR_LENGTH(TRIM(motivo)) >= 5)");
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_matricula');
    }
};