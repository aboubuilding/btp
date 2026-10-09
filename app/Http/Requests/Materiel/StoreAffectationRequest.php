<?php
namespace App\Http\Requests\Materiel;

use Illuminate\Foundation\Http\FormRequest;

class StoreAffectationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_materiel', 'directeur_technique', 'admin');
    }

    public function rules(): array
    {
        return [
            'projet_id'      => ['required', 'exists:projets,id'],
            'date_debut'     => ['required', 'date', 'before_or_equal:today'],
            'date_fin'       => ['nullable', 'date', 'after:date_debut'],
            'compteur_debut' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            $equipement = $this->route('equipement');
            if ($this->compteur_debut < $equipement->compteur_heures_actuel) {
                $validator->errors()->add('compteur_debut', 'Compteur décroissant (RG-M02).');
            }
            if ($equipement->statut !== 'disponible') {
                $validator->errors()->add('equipement_id', 'Engin non disponible (RG-M01).');
            }
            if ($equipement->assurance_expiree) {
                $validator->errors()->add('equipement_id', 'Assurance expirée — affectation impossible (RG-M04).');
            }
        }];
    }
}