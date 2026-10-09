<?php
namespace App\Http\Requests\SousTraitance;

use App\Domain\SousTraitance\Models\{ContratSousTraitant, Soustraitant};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContratSousTraitantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', ContratSousTraitant::class);
    }

    public function rules(): array
    {
        return [
            'numero_contrat'        => ['required', 'string', 'max:50', Rule::unique('contrat_sous_traitants', 'numero_contrat')],
            'sous_traitant_id'      => ['required', 'exists:soustraitants,id'],
            'projet_id'             => ['required', 'exists:projets,id'],
            'description'           => ['nullable', 'string', 'max:2000'],
            'montant'               => ['required', 'numeric', 'gt:0'],
            'taux_retenue_garantie' => ['nullable', 'numeric', 'between:0,100'],
            'montant_avenants'      => ['nullable', 'numeric', 'min:0'],
            'date_debut'            => ['required', 'date'],
            'date_fin'              => ['nullable', 'date', 'after:date_debut'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            $soustraitant = Soustraitant::find($this->sous_traitant_id);
            if ($soustraitant && $soustraitant->est_blackliste) {
                $validator->errors()->add('sous_traitant_id', 'Sous-traitant blacklisté (RG-T02).');
            }
        }];
    }
}