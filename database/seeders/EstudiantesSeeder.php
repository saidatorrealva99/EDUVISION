<?php

namespace Database\Seeders;

use App\Models\AnioEscolar;
use App\Models\Apoderado;
use App\Models\Estudiante;
use App\Models\Matricula;
use App\Models\Seccion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstudiantesSeeder extends Seeder
{
    private const POR_SECCION = 28;   // 22 secciones × 28 = 616 estudiantes

    private AnioEscolar $anio;

    // Familias ya creadas: sirven para generar hermanos
    private array $familias = [];

    public function run(): void
    {
        $this->anio = AnioEscolar::activo()->firstOrFail();

        // Todo dentro de una transacción: más rápido y, si algo falla, no queda nada a medias
        DB::transaction(function () {
            $this->crearFamiliaDemo();

            $secciones = Seccion::where('anio_escolar_id', $this->anio->id)->with('grado.nivel')->get();

            foreach ($secciones as $seccion) {
                // Las secciones de Lucía y Mateo ya tienen 1 alumno
                for ($i = $seccion->matriculas()->count(); $i < self::POR_SECCION; $i++) {
                    $this->crearEstudiante($seccion);
                }
            }
        });
    }

    // ── Familia demo para la sustentación ──────────────────────────────
    private function crearFamiliaDemo(): void
    {
        $usuarioRosa = User::factory()->create([
            'dni' => '40123456',
            'nombres' => 'Rosa María',
            'apellidos' => 'Quispe Huamán',
            'email' => 'padre@eduvision.test',
        ])->assignRole('apoderado');

        $rosa = Apoderado::factory()->create(['user_id' => $usuarioRosa->id, 'telefono' => '987654321']);

        // No se guarda en $this->familias para que ningún alumno al azar "se meta" a esta familia
        $familia = ['paterno' => 'Torres', 'materno' => 'Quispe', 'apoderados' => [['apoderado' => $rosa, 'parentesco' => 'madre']]];

        $lucia = Estudiante::factory()->create([
            'dni' => '71234567',
            'nombres' => 'Lucía',
            'apellidos' => 'Torres Quispe',
            'sexo' => 'F',
            'fecha_nacimiento' => '2012-08-14',
        ]);
        $this->vincular($lucia, $familia);
        $this->matricular($lucia, $this->buscarSeccion('Secundaria', 2, 'B'));
        $this->crearCuenta($lucia, 'estudiante@eduvision.test');

        $mateo = Estudiante::factory()->create([
            'dni' => '78765432',
            'nombres' => 'Mateo',
            'apellidos' => 'Torres Quispe',
            'sexo' => 'M',
            'fecha_nacimiento' => '2016-11-03',
        ]);
        $this->vincular($mateo, $familia);
        $this->matricular($mateo, $this->buscarSeccion('Primaria', 4, 'A'));
    }

    // ── Un estudiante al azar ──────────────────────────────────────────
    private function crearEstudiante(Seccion $seccion): void
    {
        // 12 % de probabilidad de ser hermano de un alumno ya creado
        $familia = (count($this->familias) > 20 && fake()->boolean(12))
            ? fake()->randomElement($this->familias)
            : $this->nuevaFamilia();

        $sexo = fake()->randomElement(['M', 'F']);

        $estudiante = Estudiante::factory()->create([
            'nombres' => fake()->firstName($sexo === 'M' ? 'male' : 'female'),
            'apellidos' => $familia['paterno'].' '.$familia['materno'],
            'sexo' => $sexo,
            'fecha_nacimiento' => $this->fechaNacimiento($seccion),
        ]);

        $this->vincular($estudiante, $familia);
        $this->matricular($estudiante, $seccion);

        if ($seccion->grado->nivel->nombre === 'Secundaria') {
            $this->crearCuenta($estudiante);
        }
    }

    // ── Una familia nueva con sus apoderados ───────────────────────────
    private function nuevaFamilia(): array
    {
        $paterno = fake()->lastName();
        $materno = fake()->lastName();

        // 3 de cada 6 familias registran a padre y madre
        $tipo = fake()->randomElement(['ambos', 'ambos', 'ambos', 'madre', 'padre', 'apoderado']);
        $apoderados = [];

        if (in_array($tipo, ['ambos', 'madre'])) {
            $apoderados[] = ['apoderado' => $this->crearApoderado('female', $materno.' '.fake()->lastName()), 'parentesco' => 'madre'];
        }
        if (in_array($tipo, ['ambos', 'padre'])) {
            $apoderados[] = ['apoderado' => $this->crearApoderado('male', $paterno.' '.fake()->lastName()), 'parentesco' => 'padre'];
        }
        if ($tipo === 'apoderado') {
            $genero = fake()->randomElement(['male', 'female']);
            $apoderados[] = ['apoderado' => $this->crearApoderado($genero, fake()->lastName().' '.fake()->lastName()), 'parentesco' => 'apoderado'];
        }

        $familia = ['paterno' => $paterno, 'materno' => $materno, 'apoderados' => $apoderados];
        $this->familias[] = $familia;

        return $familia;
    }

    private function crearApoderado(string $genero, string $apellidos): Apoderado
    {
        $usuario = User::factory()->create([
            'nombres' => fake()->firstName($genero),
            'apellidos' => $apellidos,
        ])->assignRole('apoderado');

        return Apoderado::factory()->create(['user_id' => $usuario->id]);
    }

    // El primer apoderado de la familia es el principal (uno solo por estudiante)
    private function vincular(Estudiante $estudiante, array $familia): void
    {
        foreach ($familia['apoderados'] as $i => $item) {
            $estudiante->apoderados()->attach($item['apoderado']->id, [
                'parentesco' => $item['parentesco'],
                'es_principal' => $i === 0,
                'activo' => true,
            ]);
        }
    }

    private function matricular(Estudiante $estudiante, Seccion $seccion): void
    {
        Matricula::create([
            'estudiante_id' => $estudiante->id,
            'seccion_id' => $seccion->id,
            'anio_escolar_id' => $this->anio->id,
            'fecha' => fake()->dateTimeBetween('2026-02-02', '2026-03-06')->format('Y-m-d'),
            'estado' => 'activa',
        ]);
    }

    // Solo Secundaria: el usuario usa el mismo DNI y nombre del estudiante
    private function crearCuenta(Estudiante $estudiante, ?string $email = null): void
    {
        $usuario = User::factory()->create([
            'dni' => $estudiante->dni,
            'nombres' => $estudiante->nombres,
            'apellidos' => $estudiante->apellidos,
            'email' => $email ?? $estudiante->dni.'@alumnos.horizonte.edu.pe',
        ])->assignRole('estudiante');

        $estudiante->update(['user_id' => $usuario->id]);
    }

    // Edad según el grado: 1.º de primaria = 6 años, 1.º de secundaria = 12 años (cumplidos al 31 de marzo)
    private function fechaNacimiento(Seccion $seccion): string
    {
        $edad = $seccion->grado->orden + ($seccion->grado->nivel->nombre === 'Primaria' ? 5 : 11);
        $anio = $this->anio->anio;

        return fake()->dateTimeBetween(($anio - $edad - 1).'-04-01', ($anio - $edad).'-03-31')->format('Y-m-d');
    }

    private function buscarSeccion(string $nivel, int $orden, string $letra): Seccion
    {
        return Seccion::where('anio_escolar_id', $this->anio->id)
            ->where('letra', $letra)
            ->whereHas('grado', fn ($q) => $q->where('orden', $orden)
                ->whereHas('nivel', fn ($q) => $q->where('nombre', $nivel)))
            ->firstOrFail();
    }
}