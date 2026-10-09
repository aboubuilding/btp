<?php
namespace App\Http\Requests\Materiel;

use Illuminate\Foundation\Http\FormRequest;

class FermerAffectationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_materiel', 'directeur_technique', 'admin');
    }

    public function rules(): array
    {
        return [
            'compteur_fin' => ['required', 'numeric', 'gte:compteur_debut'],
            'date_fin'     => ['nullable', 'date', 'after:date_debut'],
        ];
    }
}