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
    Schema::create('recursos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('curso_id')->constrained('cursos')->restrictOnDelete();
        $table->foreignId('grado_id')->constrained('grados')->restrictOnDelete();
        $table->string('titulo', 150);
        $table->enum('tipo', ['pdf', 'enlace', 'video', 'imagen']);
        $table->string('url_o_archivo', 500);
        $table->foreignId('publicado_por')->constrained('users')->restrictOnDelete();
        $table->timestamps();
        $table->softDeletes();

        $table->index(['curso_id', 'grado_id']);
    });

    DB::statement("ALTER TABLE recursos ADD CONSTRAINT chk_rec_url CHECK (tipo NOT IN ('enlace', 'video') OR url_o_archivo LIKE 'https://%')");
}

public function down(): void
{
    Schema::dropIfExists('recursos');
}
};
