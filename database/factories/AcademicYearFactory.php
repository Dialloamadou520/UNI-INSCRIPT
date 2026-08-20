<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    protected $model = AcademicYear::class;

    public function definition(): array
    {
        $debut = fake()->unique()->numberBetween(2000, 2100);

        return [
            'nom' => $debut.'-'.($debut + 1),
            'actif' => false,
        ];
    }

    public function actif(): static
    {
        return $this->state(fn () => ['actif' => true]);
    }
}
