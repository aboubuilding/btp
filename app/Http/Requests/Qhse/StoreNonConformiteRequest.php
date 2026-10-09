<?php
namespace App\Http\Requests\Qhse;

use Illuminate\Foundation\Http\FormRequest;

class StoreNonConformiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_qhse', 'chef_chantier', 'conducteur_travaux', 'admin');
    }

    public function rules(): array
    {
        return [
            'projet_id'         => ['required', 'exists:projets,id'],
            'date_constat'      => ['required', 'date', 'before_or_equal:today'],
            'ouvrage'           => ['nullable', 'string', 'max:255'],
            'description'       => ['required', 'string', 'max:5000'],
            'action_corrective' => ['nullable', 'string', 'max:2000'],
            'responsable_id'    => ['nullable', 'exists:employees,id'],
            'date_levee'        => ['nullable', 'date', 'after:date_constat'],
        ];
    }
}