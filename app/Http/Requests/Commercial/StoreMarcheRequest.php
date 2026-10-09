<?php
namespace App\Http\Requests\Commercial;

use App\Domain\Commercial\Models\Marche;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMarcheRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Marche::class);
    }

    public function rules(): array
    {
        return [
            'reference'                => ['required', 'string', 'max:50', Rule::unique('marches', 'reference')],
            'client_id'                => ['required', 'exists:clients,id'],
            'devis_id'                 => ['nullable', 'exists:devis,id'],
            'objet'                    => ['required', 'string', 'max:255'],
            'date_signature'           => ['nullable', 'date'],
            'date_ordre_service'       => ['nullable', 'date', 'after_or_equal:date_signature'],
            'montant_initial'          => ['required', 'numeric', 'min:0'],
            'delai_contractuel_jours'  => ['nullable', 'integer', 'min:1', 'max:3650'],
            'taux_avance'              => ['nullable', 'numeric', 'between:0,100'],
            'mode_remboursement_avance'=> ['nullable', 'string', 'max:100'],
            'taux_retenue_garantie'    => ['nullable', 'numeric', 'between:0,100'],
            'statut'                   => ['nullable', Rule::in(['brouillon', 'signe', 'en_cours', 'receptionne', 'clos'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'montant_initial' => (float) str_replace([' ', ','], ['', '.'], (string) $this->montant_initial),
        ]);
    }
}