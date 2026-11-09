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
    Schema::create('grados', function (Blueprint $table) {
        $table->id();
        $table->foreignId('nivel_id')->constrained('niveles')->restrictOnDelete();
        $table->string('nombre', 20);
        $table->unsignedTinyInteger('orden');

        $table->unique(['nivel_id', 'orden']);
        $table->unique(['nivel_id', 'nombre']);
    });

    DB::statement("ALTER TABLE grados ADD CONSTRAINT chk_grados_orden CHECK (orden BETWEEN 1 AND 6)");
}

public function down(): void
{
    Schema::dropIfExists('grados');
}
};
