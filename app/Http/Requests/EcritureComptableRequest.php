<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EcritureComptableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ecritureId = $this->route('id');

        return [
            'exercice_fiscal_id' => ['required', 'exists:exercices_fiscaux,id'],
            'date_ecriture' => ['required', 'date'],
            'type_reference' => ['nullable', 'string', Rule::in(array_keys(\App\Models\EcritureComptable::getTypesReference()))],
            'reference_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string', 'max:1000'],
            'lignes' => ['required', 'array', 'min:2'],
            'lignes.*.compte_id' => ['required', 'exists:plan_comptables,id'],
            'lignes.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lignes.*.credit' => ['nullable', 'numeric', 'min:0'],
            'lignes.*.description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'exercice_fiscal_id.required' => 'L\'exercice fiscal est obligatoire.',
            'exercice_fiscal_id.exists' => 'L\'exercice fiscal sélectionné n\'existe pas.',
            'date_ecriture.required' => 'La date d\'écriture est obligatoire.',
            'type_reference.in' => 'Le type de référence sélectionné n\'est pas valide.',
            'lignes.required' => 'Au moins deux lignes d\'écriture sont requises.',
            'lignes.min' => 'Au moins deux lignes d\'écriture sont requises.',
            'lignes.*.compte_id.required' => 'Le compte est obligatoire pour chaque ligne.',
            'lignes.*.compte_id.exists' => 'Le compte sélectionné n\'existe pas.',
            'lignes.*.debit.min' => 'Le débit doit être supérieur ou égal à 0.',
            'lignes.*.credit.min' => 'Le crédit doit être supérieur ou égal à 0.',
        ];
    }

    public function attributes(): array
    {
        return [
            'exercice_fiscal_id' => 'exercice fiscal',
            'date_ecriture' => 'date d\'écriture',
            'type_reference' => 'type de référence',
            'reference_id' => 'référence',
            'description' => 'description',
            'lignes' => 'lignes d\'écriture',
            'lignes.*.compte_id' => 'compte',
            'lignes.*.debit' => 'débit',
            'lignes.*.credit' => 'crédit',
            'lignes.*.description' => 'description de la ligne',
        ];
    }
}
