<?php
namespace App\Http\Requests\Execution;

use App\Domain\Execution\Models\Projet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Projet::class);
    }

    public function rules(): array
    {
        return [
            'code'                  => ['required', 'string', 'max:30', Rule::unique('projets', 'code')],
            'nom'                   => ['required', 'string', 'max:255'],
            'client_id'             => ['required', 'exists:clients,id'],
            'marche_id'             => ['nullable', 'exists:marches,id'],
            'type'                  => ['required', Rule::in(['batiment', 'route', 'ouvrage_art', 'terrassement', 'reseau'])],
            'ville'                 => ['required', 'string', 'max:100'],
            'adresse'               => ['nullable', 'string', 'max:255'],
            'latitude'              => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'             => ['nullable', 'numeric', 'between:-180,180'],
            'conducteur_travaux_id' => ['required', 'exists:employees,id'],
            'chef_chantier_id'      => ['nullable', 'exists:employees,id', 'different:conducteur_travaux_id'],
            'date_debut_prevue'     => ['required', 'date'],
            'date_fin_prevue'       => ['required', 'date', 'after_or_equal:date_debut_prevue'],
            'montant_contrat'       => ['required', 'numeric', 'min:0'],
            'budget_prevu'          => ['nullable', 'numeric', 'min:0'],
            'description'           => ['nullable', 'string'],
            'statut'                => ['nullable', Rule::in(['planifie', 'en_cours', 'suspendu', 'termine', 'annule'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'montant_contrat' => $this->normaliserMontant($this->montant_contrat),
            'budget_prevu'    => $this->normaliserMontant($this->budget_prevu),
        ]);
    }

    private function normaliserMontant($valeur): ?float
    {
        if ($valeur === null || $valeur === '') return null;
        return (float) str_replace([' ', ',', 'FCFA'], ['', '.', ''], (string) $valeur);
    }
}