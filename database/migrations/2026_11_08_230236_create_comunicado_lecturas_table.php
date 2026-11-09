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
    Schema::create('comunicado_lecturas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('comunicado_id')->constrained('comunicados')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->dateTime('leido_at')->nullable();
        $table->dateTime('confirmado_at')->nullable();

        $table->unique(['comunicado_id', 'user_id']);
    });

    DB::statement("ALTER TABLE comunicado_lecturas ADD CONSTRAINT chk_lect_orden CHECK (confirmado_at IS NULL OR leido_at IS NOT NULL)");
}

public function down(): void
{
    Schema::dropIfExists('comunicado_lecturas');
}
};
