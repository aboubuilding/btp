<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExerciceFiscalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $exerciceId = $this->route('id');

        return [
            'nom' => ['required', 'string', 'max:255'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after:date_debut'],
            'statut' => ['nullable', 'string', Rule::in(array_keys(\App\Models\ExerciceFiscal::getStatuts()))],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de l\'exercice est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.after' => 'La date de fin doit être postérieure à la date de début.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nom' => 'nom de l\'exercice',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
            'statut' => 'statut',
        ];
    }
}
