<?php
namespace App\Http\Requests\Finances;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFactureRequest extends FormRequest
{
    public function authorize(): bool
    {
        $facture = $this->route('facture');
        return $this->user()->can('update', $facture)
            && !in_array($facture->statut, ['payee', 'annulee'], true);
    }

    public function rules(): array
    {
        return [
            'type'           => ['required', Rule::in(['client', 'fournisseur'])],
            'projet_id'      => ['nullable', 'exists:projets,id'],
            'date_facture'   => ['required', 'date'],
            'date_echeance'  => ['nullable', 'date', 'after_or_equal:date_facture'],
            'montant_ht'     => ['required', 'numeric', 'gt:0'],
            'tva'            => ['nullable', 'numeric', 'min:0'],
            'montant_ttc'    => ['required', 'numeric', 'gt:0'],
            'net_a_payer'    => ['required', 'numeric', 'gt:0'],
        ];
    }
}