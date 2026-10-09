<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\Inventaire;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventaireValide
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Inventaire $inventaire,
        public int $valideParId,
    ) {}

    public function toArray(): array
    {
        return [
            'inventaire_id' => $this->inventaire->id,
            'entrepot_id'   => $this->inventaire->entrepot_id,
            'ecart_total'   => (float) $this->inventaire->ecart_total,
            'valeur_ecart'  => (float) $this->inventaire->valeur_ecart,
        ];
    }
}