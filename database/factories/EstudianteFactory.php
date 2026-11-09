<?php

namespace Database\Factories;

use App\Models\Estudiante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Estudiante>
 */
class EstudianteFactory extends Factory
{
    public function definition(): array
    {
        $sexo = fake()->randomElement(['M', 'F']);

        return [
            'dni' => fake()->unique()->numerify('6#######'),                     // estudiantes generados: 6xxxxxxx
            'nombres' => fake()->firstName($sexo === 'M' ? 'male' : 'female'),   // el nombre coincide con el sexo
            'apellidos' => fake()->lastName().' '.fake()->lastName(),
            'fecha_nacimiento' => fake()->dateTimeBetween('2009-04-01', '2020-03-31')->format('Y-m-d'),
            'sexo' => $sexo,
            // codigo_qr no va aquí: lo genera el modelo en booted()
        ];
    }
}