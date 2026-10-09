<?php
namespace App\Http\Requests\Personnel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('employe'));
    }

    public function rules(): array
    {
        $employeId = $this->route('employe')->id;

        return [
            'matricule'       => ['required', 'string', 'max:30', Rule::unique('employees', 'matricule')->ignore($employeId)],
            'nom'             => ['required', 'string', 'max:100'],
            'prenom'          => ['required', 'string', 'max:100'],
            'date_naissance'  => ['nullable', 'date', 'before:today'],
            'piece_identite'  => ['nullable', 'string', 'max:100'],
            'numero_cnss'     => ['nullable', 'string', 'max:50'],
            'date_embauche'   => ['nullable', 'date'],
            'departement_id'  => ['nullable', 'exists:departements,id'],
            'poste_id'        => ['nullable', 'exists:postes,id'],
            'type_contrat'    => ['required', Rule::in(['cdi', 'cdd', 'journalier', 'stage', 'prestataire'])],
            'salaire_base'    => ['required', 'numeric', 'min:0'],
            'mode_paiement'   => ['required', Rule::in(['especes', 'cheque', 'virement', 'mobile_money'])],
            'telephone'       => ['nullable', 'string', 'max:30'],
            'contact_urgence' => ['nullable', 'string', 'max:150'],
            'statut'          => ['required', Rule::in(['actif', 'inactif', 'suspendu'])],
        ];
    }
}