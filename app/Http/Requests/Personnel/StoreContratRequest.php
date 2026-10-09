<?php
namespace App\Http\Requests\Personnel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('rh', 'direction', 'admin');
    }

    public function rules(): array
    {
        return [
            'numero'       => ['required', 'string', 'max:50', Rule::unique('contrats', 'numero')],
            'employee_id'  => ['required', 'exists:employees,id'],
            'type'         => ['required', Rule::in(['cdi', 'cdd', 'journalier', 'stage', 'prestataire'])],
            'date_debut'   => ['required', 'date'],
            'date_fin'     => ['nullable', 'date', 'after:date_debut'],
            'salaire_base' => ['required', 'numeric', 'min:0'],
        ];
    }
}