<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DebtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomFloat(2, 10, 500000), // Genera un número decimal aleatorio con 2 decimales entre 10 y 500000
            'state' => fake()->boolean, // Genera un valor booleano aleatorio (true o false)
        ];
    }

    public function state_false(): Factory
    {
        return $this->state(function (array $attributes ) {
            return [
                'state' => false,
            ];
        });
    }
}
