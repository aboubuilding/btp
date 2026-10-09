<?php
namespace App\Http\Requests\Materiel;

use App\Domain\ParcMateriel\Models\Equipement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEquipementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Equipement::class);
    }

    public function rules(): array
    {
        return [
            'code'                    => ['required', 'string', 'max:30', Rule::unique('equipements', 'code')],
            'nom'                     => ['required', 'string', 'max:150'],
            'categorie_id'            => ['nullable', 'exists:categorie_equipements,id'],
            'marque'                  => ['nullable', 'string', 'max:100'],
            'modele'                  => ['nullable', 'string', 'max:100'],
            'numero_serie'            => ['nullable', 'string', 'max:100'],
            'numero_immatriculation'  => ['nullable', 'string', 'max:50', Rule::unique('equipements', 'numero_immatriculation')],
            'propriete'               => ['required', Rule::in(['propre', 'louee'])],
            'fournisseur_loueur_id'   => ['nullable', 'exists:fournisseurs,id'],
            'date_achat'              => ['nullable', 'date', 'before_or_equal:today'],
            'prix_achat'              => ['nullable', 'numeric', 'min:0'],
            'valeur_actuelle'         => ['nullable', 'numeric', 'min:0'],
            'cout_horaire'            => ['nullable', 'numeric', 'min:0'],
            'compteur_heures_actuel'  => ['nullable', 'numeric', 'min:0'],
            'statut'                  => ['required', Rule::in(['disponible', 'en_service', 'en_panne', 'en_maintenance', 'hors_service'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('numero_immatriculation')) {
            $this->merge([
                'numero_immatriculation' => strtoupper(trim($this->numero_immatriculation)),
            ]);
        }
    }
}