<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'statut' => Registration::STATUT_EN_ATTENTE,
            'date_soumission' => now(),
        ];
    }

    public function statut(string $statut): static
    {
        return $this->state(fn () => ['statut' => $statut]);
    }
}
