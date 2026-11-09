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
    Schema::create('competencias', function (Blueprint $table) {
        $table->id();
        $table->foreignId('curso_id')->constrained('cursos')->restrictOnDelete();
        $table->string('nombre', 200);
        $table->unsignedTinyInteger('orden');

        $table->unique(['curso_id', 'orden']);
    });

    DB::statement("ALTER TABLE competencias ADD CONSTRAINT chk_competencias_orden CHECK (orden BETWEEN 1 AND 10)");
}

public function down(): void
{
    Schema::dropIfExists('competencias');
}
};
