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
    Schema::create('encuestas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('anio_escolar_id')->constrained('anios_escolares')->restrictOnDelete();
        $table->foreignId('nivel_id')->constrained('niveles')->restrictOnDelete();
        $table->string('titulo', 150);
        $table->dateTime('inicio');
        $table->dateTime('fin');
        $table->unsignedTinyInteger('minimo_respuestas')->default(10)->comment('RN-08');
        $table->timestamps();
    });

    DB::statement("ALTER TABLE encuestas ADD CONSTRAINT chk_enc_fechas CHECK (fin > inicio)");
    DB::statement("ALTER TABLE encuestas ADD CONSTRAINT chk_enc_minimo CHECK (minimo_respuestas >= 5)");
}

public function down(): void
{
    Schema::dropIfExists('encuestas');
}
};
