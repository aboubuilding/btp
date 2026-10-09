<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\MouvementStock;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SortieStockEnregistree
{
    use Dispatchable, SerializesModels;

    public function __construct(public MouvementStock $mouvement) {}

    public function toArray(): array
    {
        return [
            'mouvement_id'  => $this->mouvement->id,
            'entrepot_id'   => $this->mouvement->entrepot_id,
            'materiau_id'   => $this->mouvement->materiau_id,
            'projet_id'     => $this->mouvement->projet_id,
            'quantite'      => (float) $this->mouvement->quantite,
            'cout_total'    => (float) $this->mouvement->cout_total,
        ];
    }
}