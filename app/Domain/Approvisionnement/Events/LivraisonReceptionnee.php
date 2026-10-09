<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\Livraison;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LivraisonReceptionnee
{
    use Dispatchable, SerializesModels;

    public function __construct(public Livraison $livraison) {}

    public function toArray(): array
    {
        return [
            'livraison_id'  => $this->livraison->id,
            'numero'        => $this->livraison->numero,
            'bon_commande_id' => $this->livraison->bon_commande_id,
            'entrepot_id'   => $this->livraison->entrepot_id,
            'date_livraison'=> $this->livraison->date_livraison?->toDateString(),
            'statut'        => $this->livraison->statut,
            'nb_articles'   => $this->livraison->articles->count(),
        ];
    }
}