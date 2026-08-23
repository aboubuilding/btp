<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanComptableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $compteId = $this->route('id');

        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('plan_comptables')->ignore($compteId)],
            'nom' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(array_keys(\App\Models\PlanComptable::getTypes()))],
            'parent_id' => ['nullable', 'exists:plan_comptables,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Le code du compte est obligatoire.',
            'code.unique' => 'Ce code de compte est déjà utilisé.',
            'code.max' => 'Le code ne doit pas dépasser 20 caractères.',
            'nom.required' => 'Le nom du compte est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'type.required' => 'Le type de compte est obligatoire.',
            'type.in' => 'Le type sélectionné n\'est pas valide.',
            'parent_id.exists' => 'Le compte parent sélectionné n\'existe pas.',
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'code du compte',
            'nom' => 'nom du compte',
            'type' => 'type de compte',
            'parent_id' => 'compte parent',
        ];
    }
}
