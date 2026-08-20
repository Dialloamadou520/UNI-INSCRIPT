<?php

namespace Database\Factories;

use App\Models\Filiere;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Filiere>
 */
class FiliereFactory extends Factory
{
    protected $model = Filiere::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->unique()->words(2, true),
            'code' => mb_strtoupper(fake()->unique()->bothify('???##')),
        ];
    }
}
