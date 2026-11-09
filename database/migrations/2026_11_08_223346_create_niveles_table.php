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
    Schema::create('niveles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('institucion_id')->constrained('instituciones')->restrictOnDelete();
        $table->string('nombre', 30);
        $table->foreignId('escala_id')->constrained('escalas')->restrictOnDelete();
        $table->timestamps();

        $table->unique(['institucion_id', 'nombre']);
    });
}

public function down(): void
{
    Schema::dropIfExists('niveles');
}
};
