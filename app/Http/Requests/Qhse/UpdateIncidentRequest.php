<?php
namespace App\Http\Requests\Qhse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_qhse', 'directeur_technique', 'direction', 'admin');
    }

    public function rules(): array
    {
        return [
            'type'                => ['required', Rule::in(['accident_travail', 'presque_accident', 'incident_materiel', 'environnement'])],
            'gravite'             => ['required', Rule::in(['mineure', 'moyenne', 'grave', 'mortelle'])],
            'description'         => ['required', 'string', 'max:5000'],
            'nombre_victimes'     => ['nullable', 'integer', 'min:0'],
            'jours_arret'         => ['nullable', 'integer', 'min:0'],
            'causes'              => ['nullable', 'string', 'max:2000'],
            'actions_correctives' => ['nullable', 'string', 'max:2000'],
            'statut'              => ['nullable', Rule::in(['declare', 'en_analyse', 'clos'])],
        ];
    }
}