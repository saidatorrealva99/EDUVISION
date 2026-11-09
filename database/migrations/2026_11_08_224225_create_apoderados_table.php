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
    Schema::create('apoderados', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->unique()->constrained('users')->restrictOnDelete()
              ->comment('El DNI y el nombre están en users');
        $table->string('telefono', 15)->nullable();
        $table->string('direccion', 200)->nullable();
        $table->dateTime('consentimiento_datos_at')->nullable()->comment('Ley 29733');
        $table->timestamps();
        $table->softDeletes();
    });

    DB::statement("ALTER TABLE apoderados ADD CONSTRAINT chk_apo_tel CHECK (telefono IS NULL OR telefono REGEXP '^\\\\+?[0-9]{7,12}$')");
}

public function down(): void
{
    Schema::dropIfExists('apoderados');
}
};
