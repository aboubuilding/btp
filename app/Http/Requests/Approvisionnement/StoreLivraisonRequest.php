<?php
namespace App\Http\Requests\Approvisionnement;

use App\Domain\Approvisionnement\Models\{BonCommande, Livraison};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLivraisonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Livraison::class);
    }

    public function rules(): array
    {
        return [
            'numero'                            => ['required', 'string', 'max:50'],
            'bon_commande_id'                   => ['required', 'exists:bon_commands,id'],
            'entrepot_id'                       => ['required', 'exists:entrepots,id'],
            'date_livraison'                    => ['required', 'date', 'before_or_equal:today'],
            'receptionnaire_id'                 => ['required', 'exists:employees,id'],
            'articles'                          => ['required', 'array', 'min:1'],
            'articles.*.materiau_id'            => ['required', 'exists:materiaux,id'],
            'articles.*.quantite_commandee'     => ['required', 'numeric', 'gte:0'],
            'articles.*.quantite_recue'         => ['required', 'numeric', 'gte:0'],
            'articles.*.etat_article'           => ['required', Rule::in(['bon_etat', 'endommage', 'non_conforme'])],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            $bc = BonCommande::find($this->bon_commande_id);
            if (!$bc || !in_array($bc->statut, ['envoye', 'confirme', 'livre_partiellement'])) {
                $validator->errors()->add('bon_commande_id', 'Le BC doit être envoyé, confirmé ou partiellement livré.');
                return;
            }

            foreach ($this->input('articles', []) as $i => $article) {
                $ligneBC = $bc->articles()->where('materiau_id', $article['materiau_id'])->first();
                if ($ligneBC && (float) $article['quantite_recue'] > (float) $ligneBC->reste_a_livrer) {
                    $validator->errors()->add(
                        "articles.{$i}.quantite_recue",
                        "Quantité reçue supérieure au reste à livrer (RG-A03)."
                    );
                }
            }
        }];
    }
}