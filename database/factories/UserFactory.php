<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'ci' => fake()->unique()->numerify('########'), // Genera un número único de 10 dígitos
            'name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'born_day' => fake()->dateTimeBetween('1970-01-01', now())->format('Y-m-d'), // Genera una fecha de nacimiento entre 2000-01-01 y la fecha actual

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    
    public function born_name_update(): Factory
    {
        $sequence = new Sequence(
            '1997-12-12',
            '1997-05-13'
        );
        return $this->state(function (array $attributes ) use ($sequence) {
            return [
                'born_day' => $sequence,
            ];
        });
    }
}
