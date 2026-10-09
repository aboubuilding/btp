<?php
namespace App\Http\Requests\Commercial;

use App\Domain\Commercial\Models\Devis;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDevisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Devis::class);
    }

    public function rules(): array
    {
        return [
            'numero'                => ['required', 'string', 'max:50'],
            'client_id'             => ['required', 'exists:clients,id'],
            'objet'                 => ['required', 'string', 'max:255'],
            'date_devis'            => ['required', 'date'],
            'validite_jours'        => ['nullable', 'integer', 'between:1,365'],
            'coefficient_vente'     => ['nullable', 'numeric', 'between:0.5,10'],
            'taux_tva'              => ['nullable', 'numeric', 'between:0,100'],
            'lignes'                => ['required', 'array', 'min:1'],
            'lignes.*.designation'  => ['required', 'string', 'max:255'],
            'lignes.*.unite'        => ['required', 'string', 'max:20'],
            'lignes.*.quantite'     => ['required', 'numeric', 'gt:0'],
            'lignes.*.prix_unitaire'=> ['required', 'numeric', 'min:0'],
            'lignes.*.debourse_sec_unitaire' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('lignes')) {
            $lignes = collect($this->lignes)->map(function ($ligne) {
                $ligne['quantite'] = $this->normaliserNombre($ligne['quantite'] ?? 0);
                $ligne['prix_unitaire'] = $this->normaliserNombre($ligne['prix_unitaire'] ?? 0);
                $ligne['debourse_sec_unitaire'] = $this->normaliserNombre($ligne['debourse_sec_unitaire'] ?? 0);
                return $ligne;
            })->toArray();
            $this->merge(['lignes' => $lignes]);
        }
    }

    private function normaliserNombre($valeur): float
    {
        return (float) str_replace([' ', ','], ['', '.'], (string) $valeur);
    }
}