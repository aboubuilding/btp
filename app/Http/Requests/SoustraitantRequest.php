<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SoustraitantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $soustraitantId = $this->route('id');

        return [
            'nom_entreprise' => ['required', 'string', 'max:255'],
            'personne_contact' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('soustraitants')->ignore($soustraitantId)],
            'adresse' => ['nullable', 'string', 'max:500'],
            'specialite' => ['nullable', 'string', Rule::in(array_keys(\App\Models\Soustraitant::getSpecialites()))],
            'note' => ['nullable', 'integer', 'min:0', 'max:5'],
            'statut' => ['nullable', 'string', Rule::in(array_keys(\App\Models\Soustraitant::getStatuts()))],
        ];
    }

    public function messages(): array
    {
        return [
            'nom_entreprise.required' => 'Le nom de l\'entreprise est obligatoire.',
            'nom_entreprise.max' => 'Le nom de l\'entreprise ne doit pas dépasser 255 caractères.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'specialite.in' => 'La spécialité sélectionnée n\'est pas valide.',
            'note.min' => 'La note doit être comprise entre 0 et 5.',
            'note.max' => 'La note doit être comprise entre 0 et 5.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nom_entreprise' => 'nom de l\'entreprise',
            'personne_contact' => 'personne de contact',
            'telephone' => 'numéro de téléphone',
            'email' => 'adresse email',
            'adresse' => 'adresse',
            'specialite' => 'spécialité',
            'note' => 'note',
            'statut' => 'statut',
        ];
    }
}
