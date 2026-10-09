<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\BonCommande;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BonCommandeCree
{
    use Dispatchable, SerializesModels;

    public function __construct(public BonCommande $bonCommande) {}

    public function toArray(): array
    {
        return [
            'bc_id'         => $this->bonCommande->id,
            'numero'        => $this->bonCommande->numero,
            'fournisseur'   => $this->bonCommande->fournisseur?->nom,
            'montant_total' => (float) $this->bonCommande->montant_total,
            'nb_articles'   => $this->bonCommande->articles->count(),
        ];
    }
}