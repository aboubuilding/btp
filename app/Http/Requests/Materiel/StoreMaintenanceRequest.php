<?php
namespace App\Http\Requests\Materiel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_materiel', 'admin');
    }

    public function rules(): array
    {
        return [
            'equipement_id'      => ['required', 'exists:equipements,id'],
            'type'               => ['required', Rule::in(['preventive', 'corrective'])],
            'date_intervention'  => ['required', 'date', 'before_or_equal:today'],
            'cout'               => ['nullable', 'numeric', 'min:0'],
            'intervenant'        => ['nullable', 'string', 'max:150'],
            'compteur'           => ['nullable', 'numeric', 'min:0'],
            'prochaine_echeance' => ['nullable', 'date', 'after:date_intervention'],
            'description'        => ['nullable', 'string', 'max:2000'],
        ];
    }
}