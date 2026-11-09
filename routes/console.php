<?php

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Revisa TODAS las relaciones de TODOS los modelos contra la base de datos
Artisan::command('eduvision:relaciones', function () {
    $ok = 0;
    $errores = 0;

    foreach (File::files(app_path('Models')) as $archivo) {
        $clase = 'App\\Models\\'.$archivo->getFilenameWithoutExtension();
        $ref = new ReflectionClass($clase);

        foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $metodo) {
            $tipo = $metodo->getReturnType();

            // Solo métodos escritos en el propio modelo, sin parámetros y que devuelven una relación
            if ($metodo->getFileName() !== $ref->getFileName()
                || $metodo->getNumberOfParameters() > 0
                || ! $tipo instanceof ReflectionNamedType
                || ! is_subclass_of($tipo->getName(), Relation::class)) {
                continue;
            }

            try {
                $clase::has($metodo->getName())->count();   // consulta real a MySQL
                $ok++;
            } catch (Throwable $e) {
                $errores++;
                $this->error($ref->getShortName().'::'.$metodo->getName().'() → '.Str::limit($e->getMessage(), 180));
            }
        }
    }

    $this->info("Relaciones correctas: {$ok}");
    $errores
        ? $this->error("Relaciones con error: {$errores}")
        : $this->info('Todas las relaciones funcionan.');
})->purpose('Prueba todas las relaciones de los modelos de EDUVISION');