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
    Schema::create('tarea_adjuntos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tarea_id')->constrained('tareas')->cascadeOnDelete();
        $table->string('archivo')->comment('Ruta en storage');
        $table->string('nombre_original');
        $table->string('mime', 100);
        $table->unsignedInteger('tamano_kb');
        $table->timestamp('created_at')->nullable();
    });

    DB::statement("ALTER TABLE tarea_adjuntos ADD CONSTRAINT chk_adj_tamano CHECK (tamano_kb BETWEEN 1 AND 10240)");
    DB::statement("ALTER TABLE tarea_adjuntos ADD CONSTRAINT chk_adj_mime CHECK (mime IN (
        'application/pdf', 'image/jpeg', 'image/png',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
    ))");
}

public function down(): void
{
    Schema::dropIfExists('tarea_adjuntos');
}
};
