<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContratSousTraitantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $contratId = $this->route('id');

        return [
            'sous_traitant_id' => ['required', 'exists:soustraitants,id'],
            'projet_id' => ['required', 'exists:projets,id'],
            'numero_contrat' => ['required', 'string', 'max:50', Rule::unique('contrat_sous_traitants')->ignore($contratId)],
            'description' => ['nullable', 'string', 'max:500'],
            'montant' => ['required', 'numeric', 'min:0'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'statut' => ['nullable', 'string', Rule::in(array_keys(\App\Models\ContratSousTraitant::getStatuts()))],
            'chemin_fichier' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'sous_traitant_id.required' => 'Le sous-traitant est obligatoire.',
            'sous_traitant_id.exists' => 'Le sous-traitant sélectionné n\'existe pas.',
            'projet_id.required' => 'Le projet est obligatoire.',
            'projet_id.exists' => 'Le projet sélectionné n\'existe pas.',
            'numero_contrat.required' => 'Le numéro de contrat est obligatoire.',
            'numero_contrat.unique' => 'Ce numéro de contrat est déjà utilisé.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.min' => 'Le montant doit être supérieur ou égal à 0.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }

    public function attributes(): array
    {
        return [
            'sous_traitant_id' => 'sous-traitant',
            'projet_id' => 'projet',
            'numero_contrat' => 'numéro de contrat',
            'description' => 'description',
            'montant' => 'montant',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
            'statut' => 'statut',
            'chemin_fichier' => 'fichier',
        ];
    }
}
