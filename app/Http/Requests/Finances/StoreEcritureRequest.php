<?php
namespace App\Http\Requests\Finances;

use Illuminate\Foundation\Http\FormRequest;

class StoreEcritureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('comptable', 'admin');
    }

    public function rules(): array
    {
        return [
            'numero'                     => ['required', 'string', 'max:50'],
            'date_ecriture'              => ['required', 'date'],
            'libelle'                    => ['required', 'string', 'max:255'],
            'exercice_fiscal_id'         => ['required', 'exists:exercice_fiscals,id'],
            'lignes'                     => ['required', 'array', 'min:2'],
            'lignes.*.plan_comptable_id' => ['required', 'exists:plan_comptables,id'],
            'lignes.*.debit'             => ['required', 'numeric', 'gte:0'],
            'lignes.*.credit'            => ['required', 'numeric', 'gte:0'],
            'lignes.*.libelle'           => ['nullable', 'string', 'max:255'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            $debit = collect($this->lignes)->sum('debit');
            $credit = collect($this->lignes)->sum('credit');

            if (round($debit, 2) !== round($credit, 2)) {
                $validator->errors()->add('lignes', 'Écriture déséquilibrée : Σ débit ≠ Σ crédit (RG-K01).');
            }

            foreach ($this->lignes as $i => $ligne) {
                if ((float) $ligne['debit'] > 0 && (float) $ligne['credit'] > 0) {
                    $validator->errors()->add("lignes.{$i}", 'Une ligne ne peut avoir à la fois débit et crédit.');
                }
                if ((float) $ligne['debit'] == 0 && (float) $ligne['credit'] == 0) {
                    $validator->errors()->add("lignes.{$i}", 'Une ligne doit avoir débit ou crédit.');
                }
            }
        }];
    }
}