<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    // La contraseña se encripta una sola vez y se reutiliza (más rápido con cientos de usuarios)
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'dni' => fake()->unique()->numerify('3#######'),           // adultos generados: 3xxxxxxx
            'nombres' => fake()->firstName(),
            'apellidos' => fake()->lastName().' '.fake()->lastName(),   // dos apellidos, como en Perú
            'email' => fake()->unique()->userName().'@horizonte.edu.pe',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('Eduvision2026'),
            'activo' => true,
            'debe_cambiar_password' => false,
            'remember_token' => Str::random(10),
        ];
    }

    // Uso: User::factory()->debeCambiarPassword()->create()
    public function debeCambiarPassword(): static
    {
        return $this->state(fn () => ['debe_cambiar_password' => true]);
    }

    public function inactivo(): static
    {
        return $this->state(fn () => ['activo' => false]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}