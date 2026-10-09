<?php
namespace App\Http\Requests\Finances;

use App\Domain\Finances\Models\Facture;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFactureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Facture::class);
    }

    public function rules(): array
    {
        return [
            'numero_facture'       => ['required', 'string', 'max:50', Rule::unique('factures', 'numero_facture')],
            'type'                 => ['required', Rule::in(['client', 'fournisseur'])],
            'type_facturable'      => ['nullable', 'string'],
            'id_facturable'        => ['nullable', 'integer'],
            'projet_id'            => ['nullable', 'exists:projets,id'],
            'situation_id'         => ['nullable', 'exists:situations,id'],
            'bon_commande_id'      => ['nullable', 'exists:bon_commands,id'],
            'date_facture'         => ['required', 'date'],
            'date_echeance'        => ['nullable', 'date', 'after_or_equal:date_facture'],
            'montant_ht'           => ['required', 'numeric', 'gt:0'],
            'tva'                  => ['nullable', 'numeric', 'min:0'],
            'montant_ttc'          => ['required', 'numeric', 'gt:0'],
            'retenue_garantie'     => ['nullable', 'numeric', 'min:0'],
            'remboursement_avance' => ['nullable', 'numeric', 'min:0'],
            'net_a_payer'          => ['required', 'numeric', 'gt:0'],
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

            $rg = (float) $this->retenue_garantie;
            $ra = (float) $this->remboursement_avance;
            $net = (float) $this->net_a_payer;

            if (round($ttc - $rg - $ra, 2) !== round($net, 2)) {
                $validator->errors()->add('net_a_payer', 'Net à payer ≠ TTC - RG - Remb. avance.');
            }
        }];
    }
}