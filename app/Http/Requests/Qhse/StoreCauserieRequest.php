<?php
namespace App\Http\Requests\Qhse;

use Illuminate\Foundation\Http\FormRequest;

class StoreCauserieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_qhse', 'chef_chantier', 'conducteur_travaux', 'admin');
    }

    public function rules(): array
    {
        return [
            'projet_id'           => ['required', 'exists:projets,id'],
            'date'                => ['required', 'date', 'before_or_equal:today'],
            'theme'               => ['required', 'string', 'max:255'],
            'nombre_participants' => ['required', 'integer', 'min:0', 'max:500'],
            'anime_par'           => ['nullable', 'exists:employees,id'],
        ];
    }
}