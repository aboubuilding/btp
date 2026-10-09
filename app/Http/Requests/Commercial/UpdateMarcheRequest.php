<?php
namespace App\Http\Requests\Commercial;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMarcheRequest extends FormRequest
{
    public function authorize(): bool
    {
        $marche = $this->route('marche');
        return $this->user()->can('update', $marche) && $marche->statut !== 'clos';
    }

    public function rules(): array
    {
        return [
            'reference'                => ['required', 'string', 'max:50', Rule::unique('marches', 'reference')->ignore($this->route('marche')->id)],
            'client_id'                => ['required', 'exists:clients,id'],
            'objet'                    => ['required', 'string', 'max:255'],
            'date_signature'           => ['nullable', 'date'],
            'date_ordre_service'       => ['nullable', 'date'],
            'montant_initial'          => ['required', 'numeric', 'min:0'],
            'delai_contractuel_jours'  => ['nullable', 'integer', 'min:1'],
            'taux_avance'              => ['nullable', 'numeric', 'between:0,100'],
            'taux_retenue_garantie'    => ['nullable', 'numeric', 'between:0,100'],
        ];
    }
}