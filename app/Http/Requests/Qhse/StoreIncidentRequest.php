<?php
namespace App\Http\Requests\Qhse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('chef_chantier', 'conducteur_travaux', 'responsable_qhse', 'admin');
    }

    public function rules(): array
    {
        return [
            'projet_id'           => ['required', 'exists:projets,id'],
            'date_incident'       => ['required', 'date', 'before_or_equal:now'],
            'type'                => ['required', Rule::in(['accident_travail', 'presque_accident', 'incident_materiel', 'environnement'])],
            'gravite'             => ['required', Rule::in(['mineure', 'moyenne', 'grave', 'mortelle'])],
            'description'         => ['required', 'string', 'max:5000'],
            'nombre_victimes'     => ['nullable', 'integer', 'min:0', 'max:100'],
            'jours_arret'         => ['nullable', 'integer', 'min:0', 'max:3650'],
            'causes'              => ['nullable', 'string', 'max:2000'],
            'actions_correctives' => ['nullable', 'string', 'max:2000'],
        ];
    }
}