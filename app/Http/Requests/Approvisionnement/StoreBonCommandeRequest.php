<?php
namespace App\Http\Requests\Approvisionnement;

use App\Domain\Approvisionnement\Models\BonCommande;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBonCommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', BonCommande::class);
    }

    public function rules(): array
    {
        return [
            'numero'                     => ['required', 'string', 'max:50'],
            'fournisseur_id'             => ['required', Rule::exists('fournisseurs', 'id')->where('etat', 1)],
            'demande_achat_id'           => ['required', Rule::exists('demande_achats', 'id')->where('statut', 'validee')],
            'date_commande'              => ['required', 'date'],
            'date_livraison_prevue'      => ['nullable', 'date', 'after_or_equal:date_commande'],
            'articles'                   => ['required', 'array', 'min:1', 'max:100'],
            'articles.*.materiau_id'     => ['required', 'distinct', 'exists:materiaux,id'],
            'articles.*.quantite'        => ['required', 'numeric', 'gt:0'],
            'articles.*.unite'           => ['required', 'string', 'max:20'],
            'articles.*.prix_unitaire'   => ['required', 'numeric', 'min:0'],
        ];
    }
}