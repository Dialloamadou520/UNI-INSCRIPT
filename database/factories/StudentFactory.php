<?php

namespace Database\Factories;

use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'ine' => mb_strtoupper(fake()->unique()->bothify('INE######')),
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => fake()->numerify('7########'),
            'filiere_id' => Filiere::factory(),
            'niveau_id' => Niveau::factory(),
            'promotion' => (string) fake()->numberBetween(2020, 2030),
        ];
    }
}
