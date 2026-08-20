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

    /**
     * Applique la décision de l'administration : historique, commentaire et notification à l'étudiant.
     */
    public function traiter(Registration $registration, string $statut, ?string $commentaire = null): Registration
    {
        return DB::transaction(function () use ($registration, $statut, $commentaire) {
            $ancienStatut = $registration->statut;

            $registration->fill([
                'statut' => $statut,
                'commentaire_admin' => $commentaire,
                'date_validation' => $statut === Registration::STATUT_VALIDEE ? now() : null,
            ]);

            if ($statut === Registration::STATUT_VALIDEE && $registration->numero_inscription === null) {
                $registration->numero_inscription = $this->numeroInscription($registration);
            }

            $registration->save();

            $this->journaliser($registration, 'traitement', $ancienStatut, $statut, $commentaire);
            $this->notifier($registration, $statut, $commentaire);

            return $registration;
        });
    }

    /**
     * Numéro unique de la forme INS-2025-2026-000123, dérivé de l'année académique et de l'inscription.
     */
    private function numeroInscription(Registration $registration): string
    {
        $annee = $registration->academicYear?->nom ?? now()->year;

        return 'INS-'.str_replace([' ', '/'], '-', (string) $annee).'-'.str_pad((string) $registration->id, 6, '0', STR_PAD_LEFT);
    }

    private function notifier(Registration $registration, string $statut, ?string $commentaire): void
    {
        $user = $registration->student->user;

        if ($user === null) {
            return;
        }

        $messages = [
            Registration::STATUT_EN_COURS_VERIFICATION => 'Votre dossier est en cours de vérification par la cellule pédagogique.',
            Registration::STATUT_CORRECTION_DEMANDEE => 'Une correction est demandée sur votre dossier.',
            Registration::STATUT_VALIDEE => 'Votre inscription est validée. Votre reçu est disponible.',
            Registration::STATUT_REJETEE => 'Votre demande d\'inscription a été rejetée.',
        ];

        $user->notificationsInternes()->create([
            'titre' => 'Inscription : '.Registration::STATUTS[$statut],
            'message' => trim(($messages[$statut] ?? '').' '.($commentaire ?? '')),
            'lu' => false,
        ]);
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
