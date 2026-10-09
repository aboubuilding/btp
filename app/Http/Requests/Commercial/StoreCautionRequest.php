<?php
namespace App\Http\Requests\Commercial;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCautionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('marche'));
    }

    public function rules(): array
    {
        return [
            'type'          => ['required', Rule::in(['soumission', 'avance', 'bonne_execution'])],
            'banque'        => ['nullable', 'string', 'max:150'],
            'montant'       => ['required', 'numeric', 'min:0'],
            'date_emission' => ['nullable', 'date'],
            'date_echeance' => ['nullable', 'date', 'after:date_emission'],
        ];
    }
}