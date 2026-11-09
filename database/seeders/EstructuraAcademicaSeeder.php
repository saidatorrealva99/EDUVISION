<?php

namespace Database\Seeders;

use App\Models\AnioEscolar;
use App\Models\Curso;
use App\Models\Nivel;
use App\Models\Seccion;
use Illuminate\Database\Seeder;

class EstructuraAcademicaSeeder extends Seeder
{
    // Competencias del Currículo Nacional (CNEB) que se repiten entre cursos
    private const MATEMATICA = [
        'Resuelve problemas de cantidad',
        'Resuelve problemas de regularidad, equivalencia y cambio',
        'Resuelve problemas de forma, movimiento y localización',
        'Resuelve problemas de gestión de datos e incertidumbre',
    ];

    private const COMUNICACION = [
        'Se comunica oralmente en su lengua materna',
        'Lee diversos tipos de textos escritos en su lengua materna',
        'Escribe diversos tipos de textos en su lengua materna',
    ];

    private const INGLES = [
        'Se comunica oralmente en inglés como lengua extranjera',
        'Lee diversos tipos de textos escritos en inglés como lengua extranjera',
        'Escribe diversos tipos de textos en inglés como lengua extranjera',
    ];

    private const CIENCIA = [
        'Indaga mediante métodos científicos para construir sus conocimientos',
        'Explica el mundo físico basándose en conocimientos sobre los seres vivos, materia y energía, biodiversidad, Tierra y universo',
        'Diseña y construye soluciones tecnológicas para resolver problemas de su entorno',
    ];

    private const ARTE = [
        'Aprecia de manera crítica manifestaciones artístico-culturales',
        'Crea proyectos desde los lenguajes artísticos',
    ];

    private const EDUCACION_FISICA = [
        'Se desenvuelve de manera autónoma a través de su motricidad',
        'Asume una vida saludable',
        'Interactúa a través de sus habilidades sociomotrices',
    ];

    private const RELIGION = [
        'Construye su identidad como persona humana, amada por Dios, digna, libre y trascendente',
        'Asume la experiencia del encuentro personal y comunitario con Dios en su proyecto de vida',
    ];

    private const IDENTIDAD = [
        'Construye su identidad',
        'Convive y participa democráticamente en la búsqueda del bien común',
    ];

    private const SOCIALES = [
        'Construye interpretaciones históricas',
        'Gestiona responsablemente el espacio y el ambiente',
        'Gestiona responsablemente los recursos económicos',
    ];

    public function run(): void
    {
        $anio = AnioEscolar::activo()->firstOrFail();

        // [nombre del curso, abreviatura, horas semanales, competencias]
        $planes = [
            'Primaria' => [
                'grados' => 6,
                'cursos' => [
                    ['Matemática', 'MAT', 6, self::MATEMATICA],
                    ['Comunicación', 'COM', 6, self::COMUNICACION],
                    ['Personal Social', 'PS', 3, [...self::IDENTIDAD, ...self::SOCIALES]],
                    ['Ciencia y Tecnología', 'CYT', 3, self::CIENCIA],
                    ['Arte y Cultura', 'AYC', 2, self::ARTE],
                    ['Educación Física', 'EF', 3, self::EDUCACION_FISICA],
                    ['Educación Religiosa', 'REL', 1, self::RELIGION],
                    ['Inglés', 'ING', 2, self::INGLES],
                ],
            ],
            'Secundaria' => [
                'grados' => 5,
                'cursos' => [
                    ['Matemática', 'MAT', 5, self::MATEMATICA],
                    ['Comunicación', 'COM', 5, self::COMUNICACION],
                    ['Inglés', 'ING', 3, self::INGLES],
                    ['Ciencias Sociales', 'CCSS', 3, self::SOCIALES],
                    ['Desarrollo Personal, Ciudadanía y Cívica', 'DPCC', 2, self::IDENTIDAD],
                    ['Ciencia y Tecnología', 'CYT', 4, self::CIENCIA],
                    ['Educación para el Trabajo', 'EPT', 2, ['Gestiona proyectos de emprendimiento económico o social']],
                    ['Arte y Cultura', 'AYC', 2, self::ARTE],
                    ['Educación Física', 'EF', 2, self::EDUCACION_FISICA],
                    ['Educación Religiosa', 'REL', 2, self::RELIGION],
                ],
            ],
        ];

        foreach ($planes as $nombreNivel => $plan) {
            $nivel = Nivel::where('nombre', $nombreNivel)->firstOrFail();

            // 1. Grados: 1.º, 2.º, 3.º...
            for ($orden = 1; $orden <= $plan['grados']; $orden++) {
                $grado = $nivel->grados()->create(['nombre' => $orden.'.º', 'orden' => $orden]);

                // 2. Dos secciones por grado (A y B) en el año activo; el tutor se asigna después
                foreach (['A', 'B'] as $letra) {
                    Seccion::create([
                        'grado_id' => $grado->id,
                        'anio_escolar_id' => $anio->id,
                        'letra' => $letra,
                        'capacidad' => 30,
                    ]);
                }
            }

            $grados = $nivel->grados()->pluck('id');

            // 3. Cursos con sus competencias, y en qué grados se dictan
            foreach ($plan['cursos'] as [$nombre, $abreviatura, $horas, $competencias]) {
                $curso = Curso::create([
                    'nivel_id' => $nivel->id,
                    'nombre' => $nombre,
                    'abreviatura' => $abreviatura,
                ]);

                foreach ($competencias as $i => $competencia) {
                    $curso->competencias()->create(['nombre' => $competencia, 'orden' => $i + 1]);
                }

                // Llena la tabla intermedia curso_grado con las horas semanales
                $curso->grados()->attach(
                    $grados->mapWithKeys(fn ($id) => [$id => ['horas_semanales' => $horas]])->all()
                );
            }
        }
    }
}