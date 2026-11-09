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
    Schema::create('correcciones_nota', function (Blueprint $table) {
        $table->id();
        $table->foreignId('nota_id')->constrained('notas')->restrictOnDelete();
        $table->string('valor_anterior', 5);
        $table->string('valor_nuevo', 5);
        $table->string('motivo', 255);
        $table->foreignId('solicitado_por')->constrained('users')->restrictOnDelete();
        $table->foreignId('aprobado_por')->nullable()->constrained('users')->restrictOnDelete();
        $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente')->comment('RN-04');
        $table->dateTime('revisado_at')->nullable();
        $table->timestamps();

        $table->index('estado');
    });

    DB::statement("ALTER TABLE correcciones_nota ADD CONSTRAINT chk_corr_distinto CHECK (valor_anterior <> valor_nuevo)");
    DB::statement("ALTER TABLE correcciones_nota ADD CONSTRAINT chk_corr_motivo CHECK (CHAR_LENGTH(TRIM(motivo)) >= 10)");
    DB::statement("ALTER TABLE correcciones_nota ADD CONSTRAINT chk_corr_revision CHECK (estado = 'pendiente' OR revisado_at IS NOT NULL)");
}

public function down(): void
{
    Schema::dropIfExists('correcciones_nota');
}
};
