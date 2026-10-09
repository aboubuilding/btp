<?php
namespace App\Http\Requests\Qhse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePvReceptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_qhse', 'conducteur_travaux', 'admin');
    }

    public function rules(): array
    {
        return [
            'projet_id'      => ['required', 'exists:projets,id'],
            'type'           => ['required', Rule::in(['partielle', 'provisoire', 'definitive'])],
            'date_reception' => ['required', 'date', 'before_or_equal:today'],
            'avec_reserves'  => ['nullable', 'boolean'],
            'chemin_fichier' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}