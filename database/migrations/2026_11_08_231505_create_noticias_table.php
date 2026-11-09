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
    Schema::create('noticias', function (Blueprint $table) {
        $table->id();
        $table->string('titulo', 150);
        $table->string('slug', 170)->unique()->comment('URL amigable');
        $table->text('cuerpo');
        $table->string('imagen')->nullable();
        $table->dateTime('publicado_at')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });

    DB::statement("ALTER TABLE noticias ADD CONSTRAINT chk_noticias_slug CHECK (slug REGEXP '^[a-z0-9]+(-[a-z0-9]+)*$')");
}

public function down(): void
{
    Schema::dropIfExists('noticias');
}
};
