<?php
namespace App\Http\Requests\Finances;

use App\Domain\Finances\Models\Depense;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Depense::class);
    }

    public function rules(): array
    {
        return [
            'projet_id'             => ['required', 'exists:projets,id'],
            'ligne_budget_id'       => ['nullable', 'exists:ligne_budgets,id'],
            'caisse_id'             => ['nullable', 'exists:caisses,id'],
            'categorie'             => ['required', Rule::in(['carburant', 'materiaux', 'sous_traitance', 'frais_generaux', 'autre'])],
            'description'           => ['required', 'string', 'max:500'],
            'montant'               => ['required', 'numeric', 'gt:0'],
            'date_depense'          => ['required', 'date', 'before_or_equal:today'],
            'document_justificatif' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}