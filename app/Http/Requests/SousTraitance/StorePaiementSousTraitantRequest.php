<?php
namespace App\Http\Requests\SousTraitance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaiementSousTraitantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('comptable', 'conducteur_travaux', 'admin');
    }

    public function rules(): array
    {
        return [
            'montant'       => ['required', 'numeric', 'gt:0'],
            'date_paiement' => ['required', 'date', 'before_or_equal:today'],
            'mode'          => ['required', Rule::in(['especes', 'cheque', 'virement', 'mobile_money'])],
            'reference'     => ['nullable', 'string', 'max:100'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            $contrat = $this->route('contrat');
            $totalApres = $contrat->total_paye + (float) $this->montant;

            if ($totalApres > $contrat->plafond_paiement) {
                $validator->errors()->add(
                    'montant',
                    'Ce paiement dépasserait le plafond contractuel (RG-T01).'
                );
            }
        }];
    }
}