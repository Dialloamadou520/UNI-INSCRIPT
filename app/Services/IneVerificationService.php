<?php

namespace App\Services;

use App\Models\Student;

class IneVerificationService
{
    public const SESSION_KEY = 'ine_verifie';

    public const MESSAGE_INTROUVABLE = "Votre INE n'a pas été trouvé dans la base des étudiants. Veuillez contacter la cellule pédagogique.";

    public const MESSAGE_DEJA_UTILISE = 'Un compte existe déjà pour cet INE. Veuillez vous connecter.';

    /**
     * Retourne l'étudiant correspondant à l'INE, ou null s'il est introuvable.
     */
    public function trouver(string $ine): ?Student
    {
        return Student::with(['filiere', 'niveau'])
            ->where('ine', mb_strtoupper(trim($ine)))
            ->first();
    }

    public function possedeDejaUnCompte(Student $student): bool
    {
        return $student->user_id !== null;
    }
}
