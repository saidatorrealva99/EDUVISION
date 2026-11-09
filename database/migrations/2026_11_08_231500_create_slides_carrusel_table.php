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
    Schema::create('slides_carrusel', function (Blueprint $table) {
        $table->id();
        $table->string('imagen');
        $table->string('titulo', 100)->nullable();
        $table->string('subtitulo', 200)->nullable();
        $table->string('enlace')->nullable();
        $table->unsignedTinyInteger('orden')->unique();
        $table->boolean('activo')->default(true);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('slides_carrusel');
}
};
