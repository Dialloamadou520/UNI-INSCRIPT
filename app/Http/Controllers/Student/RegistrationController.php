<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\DossierRequest;
use App\Models\AcademicYear;
use App\Models\Registration;
use App\Models\Student;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function __construct(private readonly RegistrationService $registrations) {}

    /**
     * Récapitulatif du dossier et de son statut.
     */
    public function show(): View
    {
        $student = $this->etudiant();
        $anneeActive = AcademicYear::active();

        return view('student.inscription.show', [
            'student' => $student,
            'anneeActive' => $anneeActive,
            'registration' => $this->inscriptionCourante($student, $anneeActive),
        ]);
    }

    /**
     * Formulaire de complétion ou de correction du dossier.
     */
    public function edit(): View|RedirectResponse
    {
        $student = $this->etudiant();
        $anneeActive = AcademicYear::active();
        $registration = $this->inscriptionCourante($student, $anneeActive);

        if ($registration !== null && ! $registration->estModifiable()) {
            return redirect()->route('student.inscription.show')
                ->with('erreur', "Votre dossier n'est plus modifiable pour le moment.");
        }

        return view('student.inscription.edit', [
            'student' => $student,
            'anneeActive' => $anneeActive,
            'registration' => $registration,
        ]);
    }

    public function update(DossierRequest $request): RedirectResponse
    {
        $student = $this->etudiant();
        $anneeActive = AcademicYear::active();

        if ($anneeActive === null) {
            return redirect()->route('student.inscription.show')
                ->with('erreur', "Aucune année académique n'est ouverte pour le moment.");
        }

        $registration = $this->inscriptionCourante($student, $anneeActive);

        if ($registration !== null && ! $registration->estModifiable()) {
            return redirect()->route('student.inscription.show')
                ->with('erreur', "Votre dossier n'est plus modifiable pour le moment.");
        }

        $this->registrations->soumettre($student, $anneeActive, $request->validated());

        return redirect()->route('student.inscription.show')
            ->with('status', 'Votre demande d\'inscription a été soumise. Statut : en attente.');
    }

    private function etudiant(): Student
    {
        return auth()->user()->student()->with(['filiere', 'niveau'])->firstOrFail();
    }

    private function inscriptionCourante(Student $student, ?AcademicYear $anneeActive): ?Registration
    {
        if ($anneeActive === null) {
            return null;
        }

        return $student->registrations()
            ->with(['histories.user', 'academicYear'])
            ->where('academic_year_id', $anneeActive->id)
            ->first();
    }
}
