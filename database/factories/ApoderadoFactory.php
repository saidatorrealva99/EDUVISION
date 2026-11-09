<?php

namespace Database\Factories;

use App\Models\Apoderado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Apoderado>
 */
class ApoderadoFactory extends Factory
{
    private const DISTRITOS = ['San Borja', 'Santiago de Surco', 'La Molina', 'Ate', 'San Miguel', 'Jesús María', 'Pueblo Libre', 'Lince'];

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),                       // crea su usuario si no se le pasa uno
            'telefono' => fake()->numerify('9########'),        // celular peruano: 9 dígitos
            'direccion' => fake()->randomElement(['Av.', 'Jr.', 'Calle']).' '.fake()->lastName().' '
                .fake()->numberBetween(100, 1999).', '.fake()->randomElement(self::DISTRITOS),
            'consentimiento_datos_at' => now(),                 // aceptó el tratamiento de datos (Ley 29733)
        ];
    }
}