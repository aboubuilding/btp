<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\MouvementStock;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EntreeStockEnregistree
{
    use Dispatchable, SerializesModels;

    public function __construct(public MouvementStock $mouvement) {}

    public function toArray(): array
    {
        return [
            'mouvement_id'  => $this->mouvement->id,
            'entrepot_id'   => $this->mouvement->entrepot_id,
            'materiau_id'   => $this->mouvement->materiau_id,
            'quantite'      => (float) $this->mouvement->quantite,
            'prix_unitaire' => (float) $this->mouvement->prix_unitaire,
            'date'          => $this->mouvement->date_mouvement?->toDateString(),
        ];
    }
}