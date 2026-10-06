<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FactureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $factureId = $this->route('id');

        return [
            'type' => ['required', 'string', Rule::in(array_keys(\App\Models\Facture::getTypes()))],
            'type_facturable' => ['required', 'string', Rule::in(array_keys(\App\Models\Facture::getTypesFacturable()))],
            'id_facturable' => ['required', 'integer'],
            'projet_id' => ['nullable', 'exists:projets,id'],
            'numero_facture' => ['required', 'string', 'max:50', Rule::unique('factures')->ignore($factureId)],
            'date_facture' => ['required', 'date'],
            'date_echeance' => ['nullable', 'date', 'after_or_equal:date_facture'],
            'montant_ht' => ['required', 'numeric', 'min:0'],
            'tva' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'statut' => ['nullable', 'string', Rule::in(array_keys(\App\Models\Facture::getStatuts()))],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Le type de facture est obligatoire.',
            'type.in' => 'Le type sélectionné n\'est pas valide.',
            'type_facturable.required' => 'Le type de tiers est obligatoire.',
            'type_facturable.in' => 'Le type de tiers sélectionné n\'est pas valide.',
            'id_facturable.required' => 'Le tiers est obligatoire.',
            'projet_id.exists' => 'Le projet sélectionné n\'existe pas.',
            'numero_facture.required' => 'Le numéro de facture est obligatoire.',
            'numero_facture.unique' => 'Ce numéro de facture est déjà utilisé.',
            'date_facture.required' => 'La date de facture est obligatoire.',
            'date_echeance.after_or_equal' => 'La date d\'échéance doit être postérieure ou égale à la date de facture.',
            'montant_ht.required' => 'Le montant HT est obligatoire.',
            'montant_ht.min' => 'Le montant HT doit être supérieur ou égal à 0.',
            'tva.min' => 'La TVA doit être supérieure ou égale à 0.',
            'tva.max' => 'La TVA ne doit pas dépasser 100%.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => 'type de facture',
            'type_facturable' => 'type de tiers',
            'id_facturable' => 'tiers',
            'projet_id' => 'projet',
            'numero_facture' => 'numéro de facture',
            'date_facture' => 'date de facture',
            'date_echeance' => 'date d\'échéance',
            'montant_ht' => 'montant HT',
            'tva' => 'TVA',
            'statut' => 'statut',
        ];
    }
}
