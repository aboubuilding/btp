<?php
namespace App\Http\Requests\Qhse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReserveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_qhse', 'conducteur_travaux', 'admin');
    }

    public function rules(): array
    {
        return [
            'localisation'     => ['required', 'string', 'max:255'],
            'description'      => ['required', 'string', 'max:2000'],
            'type_responsable' => ['required', Rule::in(['Employe', 'Soustraitant'])],
            'responsable_id'   => ['required', 'integer', 'min:1'],
            'date_limite'      => ['nullable', 'date', 'after:today'],
        ];
    }
}