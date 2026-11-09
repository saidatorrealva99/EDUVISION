<?php

namespace Database\Seeders;

use App\Models\AnioEscolar;
use App\Models\Escala;
use App\Models\Institucion;
use App\Models\Nivel;
use App\Models\ReglaRiesgo;
use Illuminate\Database\Seeder;

class CatalogosSeeder extends Seeder
{
    public function run(): void
    {
        // 1. La institución (ficticia)
        $institucion = Institucion::create([
            'nombre' => 'I.E.P. Horizonte del Saber',
            'codigo_modular' => '1234567',
            'direccion' => 'Av. Los Sauces 450, Lima',
            'telefono' => '014567890',
            'email' => 'contacto@horizonte.edu.pe',
            'color_primario' => '#4F46E5',
            'mision' => 'Formar estudiantes críticos, creativos y solidarios, acompañando a cada familia con información oportuna sobre el aprendizaje de sus hijos.',
            'vision' => 'Ser al 2030 un colegio referente en Lima por su gestión basada en datos y su atención personalizada a cada estudiante.',
        ]);

        // 2. Escalas de calificación
        $literal = Escala::create(['nombre' => 'Literal MINEDU', 'tipo' => 'literal']);
        Escala::create(['nombre' => 'Vigesimal', 'tipo' => 'numerica']);

        // minimo y maximo: equivalencia numérica para calcular promedios y detectar caídas
        $valores = [
            ['codigo' => 'AD', 'descripcion' => 'Logro destacado', 'minimo' => 18, 'maximo' => 20, 'orden' => 1],
            ['codigo' => 'A', 'descripcion' => 'Logro esperado', 'minimo' => 14, 'maximo' => 17.99, 'orden' => 2],
            ['codigo' => 'B', 'descripcion' => 'En proceso', 'minimo' => 11, 'maximo' => 13.99, 'orden' => 3],
            ['codigo' => 'C', 'descripcion' => 'En inicio', 'minimo' => 0, 'maximo' => 10.99, 'orden' => 4],
        ];
        foreach ($valores as $valor) {
            $literal->valores()->create($valor);
        }

        // 3. Niveles (los dos usan la escala literal)
        foreach (['Primaria', 'Secundaria'] as $nombre) {
            Nivel::create([
                'institucion_id' => $institucion->id,
                'nombre' => $nombre,
                'escala_id' => $literal->id,
            ]);
        }

        // 4. Año escolar 2026 (el único activo: RN-02)
        $anio = AnioEscolar::create([
            'institucion_id' => $institucion->id,
            'anio' => 2026,
            'fecha_inicio' => '2026-03-09',
            'fecha_fin' => '2026-12-18',
            'estado' => 'activo',
        ]);

        // 5. Bimestres: hoy estamos en el IV, así que los tres primeros ya están cerrados
        $bimestres = [
            ['nombre' => 'I bimestre', 'orden' => 1, 'fecha_inicio' => '2026-03-09', 'fecha_fin' => '2026-05-15', 'estado' => 'cerrado'],
            ['nombre' => 'II bimestre', 'orden' => 2, 'fecha_inicio' => '2026-05-25', 'fecha_fin' => '2026-07-24', 'estado' => 'cerrado'],
            ['nombre' => 'III bimestre', 'orden' => 3, 'fecha_inicio' => '2026-08-10', 'fecha_fin' => '2026-10-09', 'estado' => 'cerrado'],
            ['nombre' => 'IV bimestre', 'orden' => 4, 'fecha_inicio' => '2026-10-19', 'fecha_fin' => '2026-12-18', 'estado' => 'abierto'],
        ];
        foreach ($bimestres as $bimestre) {
            $anio->periodos()->create($bimestre);
        }

        // 6. Reglas del sistema de alerta temprana (umbrales configurables)
        $reglas = [
            ['codigo' => 'R1', 'nombre' => 'Inasistencia reciente', 'parametros' => ['faltas' => 3, 'dias_habiles' => 10], 'puntos' => 2],
            ['codigo' => 'R2', 'nombre' => 'Inasistencia acumulada', 'parametros' => ['porcentaje' => 15], 'puntos' => 3],
            ['codigo' => 'R3', 'nombre' => 'Tardanzas', 'parametros' => ['tardanzas' => 4, 'periodo' => 'mes'], 'puntos' => 1],
            ['codigo' => 'R4', 'nombre' => 'Caída de rendimiento', 'parametros' => ['niveles' => 1, 'puntos' => 3, 'maximo_puntos' => 4], 'puntos' => 2],
            ['codigo' => 'R5', 'nombre' => 'Cursos en inicio', 'parametros' => ['un_curso' => 2, 'dos_o_mas' => 3], 'puntos' => 2],
            ['codigo' => 'R6', 'nombre' => 'Tareas sin entregar', 'parametros' => ['tareas' => 3, 'semanas' => 2], 'puntos' => 1],
            ['codigo' => 'R7', 'nombre' => 'Observación de alerta', 'parametros' => [], 'puntos' => 2],
        ];
        foreach ($reglas as $regla) {
            ReglaRiesgo::create($regla);
        }
    }
}