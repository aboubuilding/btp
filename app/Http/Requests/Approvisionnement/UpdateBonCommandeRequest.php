<?php
namespace App\Http\Requests\Approvisionnement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBonCommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $bc = $this->route('bon_commande');
        return $this->user()->can('update', $bc) && !$bc->est_fige;
    }

    public function rules(): array
    {
        return [
            'date_commande'              => ['required', 'date'],
            'date_livraison_prevue'      => ['nullable', 'date', 'after_or_equal:date_commande'],
            'articles'                   => ['required', 'array', 'min:1'],
            'articles.*.materiau_id'     => ['required', 'distinct', 'exists:materiaux,id'],
            'articles.*.quantite'        => ['required', 'numeric', 'gt:0'],
            'articles.*.unite'           => ['required', 'string', 'max:20'],
            'articles.*.prix_unitaire'   => ['required', 'numeric', 'min:0'],
        ];
    }
}