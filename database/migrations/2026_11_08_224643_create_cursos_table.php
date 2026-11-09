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
    Schema::create('cursos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('nivel_id')->constrained('niveles')->restrictOnDelete();
        $table->string('nombre', 80);
        $table->string('abreviatura', 10)->nullable();

        $table->unique(['nivel_id', 'nombre']);
    });
}

public function down(): void
{
    Schema::dropIfExists('cursos');
}
};
