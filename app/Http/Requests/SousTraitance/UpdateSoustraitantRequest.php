<?php
namespace App\Http\Requests\SousTraitance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSoustraitantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('soustraitant'));
    }

    public function rules(): array
    {
        return [
            'entreprise' => ['required', 'string', 'max:255'],
            'contact'    => ['nullable', 'string', 'max:150'],
            'telephone'  => ['nullable', 'string', 'max:30'],
            'email'      => ['nullable', 'email', 'max:150'],
            'specialite' => ['required', 'string', 'max:100'],
            'note'       => ['nullable', 'numeric', 'between:0,5'],
            'statut'     => ['required', Rule::in(['actif', 'suspendu', 'blackliste'])],
        ];
    }
}