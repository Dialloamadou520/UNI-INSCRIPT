<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $student = auth()->user()->student()->with(['filiere', 'niveau'])->firstOrFail();
        $anneeActive = AcademicYear::active();

        $registration = $anneeActive === null
            ? null
            : $student->registrations()
                ->with(['histories.user'])
                ->where('academic_year_id', $anneeActive->id)
                ->first();

        return view('student.dashboard', [
            'student' => $student,
            'anneeActive' => $anneeActive,
            'registration' => $registration,
            'progression' => $this->progression($student, $registration),
        ]);
    }

    /**
     * Progression de l'inscription en pourcentage : compte créé, dossier complété, soumis, validé.
     */
    private function progression(Student $student, ?Registration $registration): int
    {
        $etapes = [
            true,
            $student->dossierComplet(),
            $registration !== null,
            $registration?->statut === Registration::STATUT_VALIDEE,
        ];

        return (int) round(count(array_filter($etapes)) / count($etapes) * 100);
    }
}
