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
    Schema::create('caso_acciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('caso_id')->constrained('casos')->restrictOnDelete();
        $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
        $table->enum('tipo', ['llamada', 'citacion', 'refuerzo', 'derivacion', 'otro']);
        $table->text('descripcion');
        $table->dateTime('fecha');
        $table->timestamps();
    });

    DB::statement("ALTER TABLE caso_acciones ADD CONSTRAINT chk_accion_desc CHECK (CHAR_LENGTH(TRIM(descripcion)) >= 5)");
}

public function down(): void
{
    Schema::dropIfExists('caso_acciones');
}
};
