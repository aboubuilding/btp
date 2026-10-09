<?php
namespace App\Http\Requests\Materiel;

use Illuminate\Foundation\Http\FormRequest;

class StoreReleveCarburantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_materiel', 'chef_chantier', 'magasinier', 'admin');
    }

    public function rules(): array
    {
        return [
            'date_releve'   => ['required', 'date', 'before_or_equal:today'],
            'compteur'      => ['required', 'numeric', 'min:0'],
            'quantite'      => ['required', 'numeric', 'gt:0', 'max:10000'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
            'cout_total'    => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            $equipement = $this->route('equipement');
            if ($this->compteur < $equipement->compteur_heures_actuel) {
                $validator->errors()->add('compteur', 'Compteur décroissant (RG-M02).');
            }
        }];
    }
}