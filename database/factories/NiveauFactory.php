<?php

namespace Database\Factories;

use App\Models\Niveau;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Niveau>
 */
class NiveauFactory extends Factory
{
    protected $model = Niveau::class;

    public function definition(): array
    {
        return [
            'nom' => 'Licence '.fake()->unique()->numberBetween(1, 999),
            'ordre' => fake()->numberBetween(1, 5),
        ];
    }
}
