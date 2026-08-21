<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class DossierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isEtudiant() === true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'date_naissance' => ['required', 'date', 'before:today'],
            'lieu_naissance' => ['required', 'string', 'max:255'],
            'sexe' => ['required', 'in:Masculin,Féminin'],
            'nationalite' => ['required', 'string', 'max:255'],
            'adresse' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'tuteur_prenom' => ['nullable', 'string', 'max:255'],
            'tuteur_nom' => ['nullable', 'string', 'max:255'],
            'tuteur_telephone' => ['nullable', 'string', 'max:30'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'date_naissance' => 'date de naissance',
            'lieu_naissance' => 'lieu de naissance',
            'nationalite' => 'nationalité',
            'telephone' => 'téléphone',
            'tuteur_prenom' => 'prénom du tuteur',
            'tuteur_nom' => 'nom du tuteur',
            'tuteur_telephone' => 'téléphone du tuteur',
        ];
    }
}
