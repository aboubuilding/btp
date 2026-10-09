<?php
namespace App\Http\Requests\Finances;

use App\Domain\Finances\Models\Paiement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Paiement::class);
    }

    public function rules(): array
    {
        return [
            'numero_paiement'    => ['required', 'string', 'max:50'],
            'sens'               => ['required', Rule::in(['encaissement', 'decaissement'])],
            'montant'            => ['required', 'numeric', 'gt:0'],
            'date_paiement'      => ['required', 'date', 'before_or_equal:today'],
            'mode'               => ['required', Rule::in(['especes', 'cheque', 'virement', 'mobile_money'])],
            'compte_bancaire_id' => ['nullable', 'exists:compte_bancaires,id'],
            'caisse_id'          => ['nullable', 'exists:caisses,id'],
            'reference'          => ['nullable', 'string', 'max:100'],
            'facture_id'         => ['nullable', 'exists:factures,id'],
        ];
    }
}