<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaiementSousTraitantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paiementId = $this->route('id');

        return [
            'facture_sous_traitant_id' => ['required', 'exists:factures,id'],
            'montant' => ['required', 'numeric', 'min:1'],
            'date_paiement' => ['required', 'date'],
            'mode' => ['required', 'string', Rule::in(array_keys(\App\Models\PaiementSousTraitant::getModes()))],
            'reference' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'facture_sous_traitant_id.required' => 'La facture est obligatoire.',
            'facture_sous_traitant_id.exists' => 'La facture sélectionnée n\'existe pas.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.min' => 'Le montant doit être supérieur à 0.',
            'date_paiement.required' => 'La date de paiement est obligatoire.',
            'mode.required' => 'Le mode de paiement est obligatoire.',
            'mode.in' => 'Le mode de paiement sélectionné n\'est pas valide.',
            'reference.max' => 'La référence ne doit pas dépasser 100 caractères.',
        ];
    }

    public function attributes(): array
    {
        return [
            'facture_sous_traitant_id' => 'facture',
            'montant' => 'montant',
            'date_paiement' => 'date de paiement',
            'mode' => 'mode de paiement',
            'reference' => 'référence',
        ];
    }
}
