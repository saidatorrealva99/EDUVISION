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
    Schema::create('observaciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('matricula_id')->constrained('matriculas')->restrictOnDelete();
        $table->foreignId('docente_id')->constrained('docentes')->restrictOnDelete();
        $table->text('texto');
        $table->boolean('visible_familia')->default(false);
        $table->boolean('requiere_atencion')->default(false)->comment('Activa la regla R7');
        $table->timestamps();
        $table->softDeletes();
    });

    DB::statement("ALTER TABLE observaciones ADD CONSTRAINT chk_obs_texto CHECK (CHAR_LENGTH(TRIM(texto)) >= 5)");
}

public function down(): void
{
    Schema::dropIfExists('observaciones');
}
};
