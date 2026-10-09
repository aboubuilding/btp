<?php
namespace App\Http\Requests\SousTraitance;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationSousTraitantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('conducteur_travaux', 'directeur_technique', 'responsable_qhse', 'admin');
    }

    public function rules(): array
    {
        return [
            'projet_id'     => ['required', 'exists:projets,id'],
            'note_qualite'  => ['required', 'integer', 'between:0,5'],
            'note_delai'    => ['required', 'integer', 'between:0,5'],
            'note_securite' => ['required', 'integer', 'between:0,5'],
            'commentaire'   => ['nullable', 'string', 'max:1000'],
        ];
    }
}