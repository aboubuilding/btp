<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\{Materiau, NiveauStock};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockSousSeuil
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Materiau $materiau,
        public NiveauStock $niveau,
    ) {}

    public function toArray(): array
    {
        return [
            'materiau_id'   => $this->materiau->id,
            'materiau_nom'  => $this->materiau->nom,
            'entrepot_id'   => $this->niveau->entrepot_id,
            'entrepot_nom'  => $this->niveau->entrepot?->nom,
            'quantite'      => (float) $this->niveau->quantite,
            'seuil'         => (float) $this->materiau->seuil_alerte_stock_min,
            'unite'         => $this->materiau->unite,
        ];
    }
}