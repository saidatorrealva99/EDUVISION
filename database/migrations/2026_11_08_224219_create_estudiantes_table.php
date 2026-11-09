<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->restrictOnDelete()
                  ->comment('Opcional: en primaria puede no tener cuenta');
            $table->string('dni', 8)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->date('fecha_nacimiento');
            $table->enum('sexo', ['M', 'F']);
            $table->char('codigo_qr', 32)->unique()->comment('Token aleatorio del carné; nunca el DNI');
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['apellidos', 'nombres']);
        });

        DB::statement("ALTER TABLE estudiantes ADD CONSTRAINT chk_est_dni CHECK (dni REGEXP '^[0-9]{8}$')");
        DB::statement("ALTER TABLE estudiantes ADD CONSTRAINT chk_est_qr CHECK (codigo_qr REGEXP '^[A-Za-z0-9]{32}$')");
        DB::statement("ALTER TABLE estudiantes ADD CONSTRAINT chk_est_fnac CHECK (fecha_nacimiento >= '2000-01-01')");
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};