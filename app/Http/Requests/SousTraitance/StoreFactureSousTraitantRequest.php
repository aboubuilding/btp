<?php
namespace App\Http\Requests\SousTraitance;

use Illuminate\Foundation\Http\FormRequest;

class StoreFactureSousTraitantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('comptable', 'conducteur_travaux', 'admin');
    }

    public function rules(): array
    {
        return [
            'numero'                   => ['required', 'string', 'max:50'],
            'contrat_sous_traitant_id' => ['required', 'exists:contrat_sous_traitants,id'],
            'date_facture'             => ['required', 'date', 'before_or_equal:today'],
            'montant_ht'               => ['required', 'numeric', 'gt:0'],
            'tva'                      => ['nullable', 'numeric', 'min:0'],
            'montant_ttc'              => ['required', 'numeric', 'gt:0'],
            'retenue_garantie'         => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            $ht = (float) $this->montant_ht;
            $tva = (float) $this->tva;
            $ttc = (float) $this->montant_ttc;

            if (round($ht + $tva, 2) !== round($ttc, 2)) {
                $validator->errors()->add('montant_ttc', 'Montant TTC ≠ HT + TVA.');
            }
        }];
    }
}