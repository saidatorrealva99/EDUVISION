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
    Schema::create('instituciones', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 150);
        $table->string('codigo_modular', 7)->unique();
        $table->string('direccion', 200)->nullable();
        $table->string('telefono', 15)->nullable();
        $table->string('email', 150)->nullable();
        $table->string('logo')->nullable();
        $table->char('color_primario', 7)->default('#4F46E5');
        $table->text('mision')->nullable();
        $table->text('vision')->nullable();
        $table->timestamps();
    });

    DB::statement("ALTER TABLE instituciones ADD CONSTRAINT chk_inst_codigo CHECK (codigo_modular REGEXP '^[0-9]{7}$')");
    DB::statement("ALTER TABLE instituciones ADD CONSTRAINT chk_inst_color CHECK (color_primario REGEXP '^#[0-9A-Fa-f]{6}$')");
}

public function down(): void
{
    Schema::dropIfExists('instituciones');
}
};
