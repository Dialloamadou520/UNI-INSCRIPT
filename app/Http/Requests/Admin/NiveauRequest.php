<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NiveauRequest extends FormRequest
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
            'nom' => ['required', 'string', 'max:255', Rule::unique('niveaux', 'nom')->ignore($this->route('niveau'))],
            'ordre' => ['nullable', 'integer', 'min:0', 'max:99'],
        ];
    }
}
