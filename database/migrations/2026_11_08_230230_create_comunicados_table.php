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
    Schema::create('comunicados', function (Blueprint $table) {
        $table->id();
        $table->foreignId('autor_id')->constrained('users')->restrictOnDelete();
        $table->string('titulo', 150);
        $table->text('cuerpo');
        $table->enum('alcance_tipo', ['colegio', 'nivel', 'seccion', 'familia']);
        $table->unsignedBigInteger('alcance_id')->nullable()->comment('id del nivel, sección o apoderado según alcance_tipo');
        $table->boolean('requiere_confirmacion')->default(false);
        $table->dateTime('publicado_at')->nullable();
        $table->timestamps();
        $table->softDeletes();

        $table->index(['alcance_tipo', 'alcance_id']);
    });

    DB::statement("ALTER TABLE comunicados ADD CONSTRAINT chk_com_alcance CHECK (
        (alcance_tipo = 'colegio' AND alcance_id IS NULL) OR (alcance_tipo <> 'colegio' AND alcance_id IS NOT NULL)
    )");
}

public function down(): void
{
    Schema::dropIfExists('comunicados');
}
};
