<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EvaluationSousTraitantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $evaluationId = $this->route('id');

        return [
            'sous_traitant_id' => ['required', 'exists:soustraitants,id'],
            'projet_id' => ['nullable', 'exists:projets,id'],
            'evaluer_par' => ['nullable', 'exists:users,id'],
            'note_qualite' => ['required', 'integer', 'min:1', 'max:5'],
            'note_delai' => ['required', 'integer', 'min:1', 'max:5'],
            'note_securite' => ['required', 'integer', 'min:1', 'max:5'],
            'commentaires' => ['nullable', 'string', 'max:1000'],
            'date_evaluation' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'sous_traitant_id.required' => 'Le sous-traitant est obligatoire.',
            'sous_traitant_id.exists' => 'Le sous-traitant sélectionné n\'existe pas.',
            'projet_id.exists' => 'Le projet sélectionné n\'existe pas.',
            'evaluer_par.exists' => 'L\'évaluateur sélectionné n\'existe pas.',
            'note_qualite.required' => 'La note de qualité est obligatoire.',
            'note_qualite.min' => 'La note de qualité doit être comprise entre 1 et 5.',
            'note_qualite.max' => 'La note de qualité doit être comprise entre 1 et 5.',
            'note_delai.required' => 'La note de délai est obligatoire.',
            'note_delai.min' => 'La note de délai doit être comprise entre 1 et 5.',
            'note_delai.max' => 'La note de délai doit être comprise entre 1 et 5.',
            'note_securite.required' => 'La note de sécurité est obligatoire.',
            'note_securite.min' => 'La note de sécurité doit être comprise entre 1 et 5.',
            'note_securite.max' => 'La note de sécurité doit être comprise entre 1 et 5.',
            'commentaires.max' => 'Les commentaires ne doivent pas dépasser 1000 caractères.',
            'date_evaluation.required' => 'La date d\'évaluation est obligatoire.',
        ];
    }

    public function attributes(): array
    {
        return [
            'sous_traitant_id' => 'sous-traitant',
            'projet_id' => 'projet',
            'evaluer_par' => 'évaluateur',
            'note_qualite' => 'note de qualité',
            'note_delai' => 'note de délai',
            'note_securite' => 'note de sécurité',
            'commentaires' => 'commentaires',
            'date_evaluation' => 'date d\'évaluation',
        ];
    }
}
