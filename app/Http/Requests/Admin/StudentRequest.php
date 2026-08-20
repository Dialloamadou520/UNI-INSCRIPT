<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('ine')) {
            $this->merge(['ine' => Str::upper(trim($this->string('ine')))]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'ine' => ['required', 'string', 'max:50', Rule::unique('students', 'ine')->ignore($this->route('student'))],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'filiere_id' => ['nullable', 'exists:filieres,id'],
            'niveau_id' => ['nullable', 'exists:niveaux,id'],
            'promotion' => ['nullable', 'string', 'max:20'],
        ];
    }
}
