<?php
namespace App\Http\Requests\SousTraitance;

use App\Domain\SousTraitance\Models\Soustraitant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSoustraitantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Soustraitant::class);
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