<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Registration;
use App\Models\RegistrationHistory;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    /**
     * Crée ou met à jour le dossier de l'étudiant puis le soumet pour l'année active.
     *
     * @param  array<string, mixed>  $donnees
     */
    public function soumettre(Student $student, AcademicYear $anneeActive, array $donnees): Registration
    {
        return DB::transaction(function () use ($student, $anneeActive, $donnees) {
            $student->update($donnees);

            $registration = $student->registrations()->firstOrNew([
                'academic_year_id' => $anneeActive->id,
            ]);

            $ancienStatut = $registration->exists ? $registration->statut : null;

            $registration->fill([
                'statut' => Registration::STATUT_EN_ATTENTE,
                'date_soumission' => now(),
                'date_validation' => null,
            ])->save();

            $this->journaliser(
                $registration,
                $ancienStatut === null ? 'soumission' : 'resoumission',
                $ancienStatut,
                Registration::STATUT_EN_ATTENTE,
            );

            return $registration;
        });
    }

    public function journaliser(
        Registration $registration,
        string $action,
        ?string $ancienStatut,
        ?string $nouveauStatut,
        ?string $commentaire = null,
    ): RegistrationHistory {
        return $registration->histories()->create([
            'user_id' => auth()->id(),
            'action' => $action,
            'ancien_statut' => $ancienStatut,
            'nouveau_statut' => $nouveauStatut,
            'commentaire' => $commentaire,
        ]);
    }
}
