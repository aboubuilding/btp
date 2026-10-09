<?php
namespace App\Http\Requests\Materiel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEquipementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('equipement'));
    }

    public function rules(): array
    {
        $equipementId = $this->route('equipement')->id;

        return [
            'code'                    => ['required', 'string', 'max:30', Rule::unique('equipements', 'code')->ignore($equipementId)],
            'nom'                     => ['required', 'string', 'max:150'],
            'categorie_id'            => ['nullable', 'exists:categorie_equipements,id'],
            'marque'                  => ['nullable', 'string', 'max:100'],
            'modele'                  => ['nullable', 'string', 'max:100'],
            'numero_serie'            => ['nullable', 'string', 'max:100'],
            'numero_immatriculation'  => ['nullable', 'string', 'max:50', Rule::unique('equipements', 'numero_immatriculation')->ignore($equipementId)],
            'propriete'               => ['required', Rule::in(['propre', 'louee'])],
            'fournisseur_loueur_id'   => ['nullable', 'exists:fournisseurs,id'],
            'date_achat'              => ['nullable', 'date'],
            'prix_achat'              => ['nullable', 'numeric', 'min:0'],
            'valeur_actuelle'         => ['nullable', 'numeric', 'min:0'],
            'cout_horaire'            => ['nullable', 'numeric', 'min:0'],
            'compteur_heures_actuel'  => ['nullable', 'numeric', 'min:0'],
            'statut'                  => ['required', Rule::in(['disponible', 'en_service', 'en_panne', 'en_maintenance', 'hors_service'])],
        ];
    }
}