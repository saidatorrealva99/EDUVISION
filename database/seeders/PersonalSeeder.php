<?php

namespace Database\Seeders;

use App\Models\AnioEscolar;
use App\Models\Asignacion;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Nivel;
use App\Models\Seccion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PersonalSeeder extends Seeder
{
    // Abreviatura del curso → especialidad del docente
    private const ESPECIALIDAD = [
        'MAT' => 'Matemática',
        'COM' => 'Comunicación',
        'ING' => 'Inglés',
        'CCSS' => 'Ciencias Sociales',
        'DPCC' => 'Ciencias Sociales',
        'CYT' => 'Ciencia y Tecnología',
        'EPT' => 'Educación para el Trabajo',
        'AYC' => 'Arte y Cultura',
        'EF' => 'Educación Física',
        'REL' => 'Educación Religiosa',
    ];

    private AnioEscolar $anio;

    public function run(): void
    {
        $this->anio = AnioEscolar::activo()->firstOrFail();

        // ── 1. Directivos (usuarios demo) ──────────────────────────────
        $this->crearUsuario([
            'dni' => '40000002',
            'nombres' => 'Jorge Luis',
            'apellidos' => 'Ramírez Salas',
            'email' => 'director@eduvision.test',
        ], 'director');

        $this->crearUsuario([
            'dni' => '40000003',
            'nombres' => 'Patricia Elena',
            'apellidos' => 'Gómez Rivas',
            'email' => 'coordinador@eduvision.test',
        ], 'coordinador');

        // ── 2. Docente demo ────────────────────────────────────────────
        $carlos = $this->crearDocente('Matemática', [
            'dni' => '41234567',
            'nombres' => 'Carlos Alberto',
            'apellidos' => 'Mendoza Torres',
            'email' => 'docente@eduvision.test',
        ]);

        // ── 3. Secundaria: un equipo de especialistas por curso ────────
        $secundaria = Nivel::where('nombre', 'Secundaria')->firstOrFail();
        $seccionesSec = $this->seccionesDe($secundaria);

        // Cuántos docentes tiene cada curso
        $plantilla = ['MAT' => 2, 'COM' => 2, 'ING' => 1, 'CCSS' => 1, 'DPCC' => 1, 'CYT' => 2, 'EPT' => 1, 'AYC' => 1, 'EF' => 1, 'REL' => 1];

        $equipo = [];
        foreach ($plantilla as $abreviatura => $cantidad) {
            for ($i = 0; $i < $cantidad; $i++) {
                // El primer docente de Matemática es Carlos (el demo)
                $equipo[$abreviatura][] = ($abreviatura === 'MAT' && $i === 0)
                    ? $carlos
                    : $this->crearDocente(self::ESPECIALIDAD[$abreviatura]);
            }
        }

        // Reparte las secciones en bloques: con 2 docentes, el primero toma 1.º A a 3.º A y el segundo el resto
        $total = $seccionesSec->count();
        foreach (Curso::where('nivel_id', $secundaria->id)->get() as $curso) {
            $docentes = $equipo[$curso->abreviatura];
            foreach ($seccionesSec as $i => $seccion) {
                $docente = $docentes[intdiv($i * count($docentes), $total)];
                $this->asignar($docente, $curso, $seccion);
            }
        }

        // Tutores de Secundaria: Carlos en 2.º B; el resto se reparte entre los demás especialistas
        $seccionCarlos = $seccionesSec->first(fn ($s) => $s->grado->orden === 2 && $s->letra === 'B');
        $seccionCarlos->update(['tutor_id' => $carlos->id]);

        $otrosDocentes = collect($equipo)->flatten(1)->unique('id')->reject(fn ($d) => $d->id === $carlos->id)->values();
        $seccionesSec->reject(fn ($s) => $s->id === $seccionCarlos->id)->values()
            ->each(fn ($seccion, $i) => $seccion->update(['tutor_id' => $otrosDocentes[$i]->id]));

        // ── 4. Primaria: tutor polidocente + especialistas ─────────────
        $primaria = Nivel::where('nombre', 'Primaria')->firstOrFail();
        $cursosPri = Curso::where('nivel_id', $primaria->id)->get();

        $educacionFisica = $this->crearDocente('Educación Física');
        $ingles = $this->crearDocente('Inglés');
        $religion = $equipo['REL'][0]; // el de Secundaria también enseña en Primaria

        foreach ($this->seccionesDe($primaria) as $seccion) {
            $tutor = $this->crearDocente('Educación Primaria');
            $seccion->update(['tutor_id' => $tutor->id]);

            foreach ($cursosPri as $curso) {
                $docente = match ($curso->abreviatura) {
                    'EF' => $educacionFisica,
                    'ING' => $ingles,
                    'REL' => $religion,
                    default => $tutor, // MAT, COM, PS, CYT y AYC los dicta el tutor
                };
                $this->asignar($docente, $curso, $seccion);
            }
        }
    }

    // Crea un usuario con la factory y le asigna su rol
    private function crearUsuario(array $datos, string $rol): User
    {
        return User::factory()->create($datos)->assignRole($rol);
    }

    // Crea el usuario + su ficha de docente
    private function crearDocente(string $especialidad, array $datos = []): Docente
    {
        $usuario = $this->crearUsuario($datos, 'docente');

        return Docente::create(['user_id' => $usuario->id, 'especialidad' => $especialidad]);
    }

    // Secciones de un nivel en el año activo, ordenadas: 1.º A, 1.º B, 2.º A...
    private function seccionesDe(Nivel $nivel): Collection
    {
        return Seccion::query()
            ->join('grados', 'grados.id', '=', 'secciones.grado_id')
            ->where('grados.nivel_id', $nivel->id)
            ->where('secciones.anio_escolar_id', $this->anio->id)
            ->orderBy('grados.orden')
            ->orderBy('secciones.letra')
            ->select('secciones.*')
            ->with('grado')
            ->get();
    }

    private function asignar(Docente $docente, Curso $curso, Seccion $seccion): void
    {
        Asignacion::create([
            'docente_id' => $docente->id,
            'curso_id' => $curso->id,
            'seccion_id' => $seccion->id,
            'anio_escolar_id' => $this->anio->id,
        ]);
    }
}