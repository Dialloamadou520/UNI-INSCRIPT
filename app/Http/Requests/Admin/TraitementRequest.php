<?php

namespace App\Http\Requests\Admin;

use App\Models\Registration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TraitementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'statut' => ['required', Rule::in([
                Registration::STATUT_EN_COURS_VERIFICATION,
                Registration::STATUT_CORRECTION_DEMANDEE,
                Registration::STATUT_VALIDEE,
                Registration::STATUT_REJETEE,
            ])],
            'commentaire' => [
                Rule::requiredIf(fn () => in_array($this->input('statut'), [
                    Registration::STATUT_CORRECTION_DEMANDEE,
                    Registration::STATUT_REJETEE,
                ], true)),
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'commentaire.required' => 'Un commentaire est obligatoire pour un rejet ou une demande de correction.',
        ];
    }
}
