<?php
namespace App\Http\Requests\Materiel;

use Illuminate\Foundation\Http\FormRequest;

class StorePanneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_materiel', 'chef_chantier', 'conducteur_travaux', 'admin');
    }

    public function rules(): array
    {
        return [
            'date_panne'            => ['required', 'date', 'before_or_equal:today'],
            'description'           => ['required', 'string', 'max:1000'],
            'heures_immobilisation' => ['nullable', 'integer', 'min:0', 'max:8760'],
            'cout_reparation'       => ['nullable', 'numeric', 'min:0'],
        ];
    }
}