<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->route('id');

        return [
            'nom' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(array_keys(\App\Models\Client::getTypes()))],
            'personne_contact' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('clients')->ignore($clientId)],
            'adresse' => ['nullable', 'string', 'max:500'],
            'nif' => ['nullable', 'string', 'max:50', Rule::unique('clients')->ignore($clientId)],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du client est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'type.required' => 'Le type de client est obligatoire.',
            'type.in' => 'Le type sélectionné n\'est pas valide.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'nif.unique' => 'Ce numéro NIF est déjà utilisé.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nom' => 'nom du client',
            'type' => 'type de client',
            'personne_contact' => 'personne de contact',
            'telephone' => 'numéro de téléphone',
            'email' => 'adresse email',
            'adresse' => 'adresse',
            'nif' => 'numéro d\'identification fiscale',
        ];
    }
}
