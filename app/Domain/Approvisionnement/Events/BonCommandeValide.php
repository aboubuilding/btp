<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\BonCommande;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BonCommandeValide
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public BonCommande $bonCommande,
        public int $valideParId,
    ) {}

    public function toArray(): array
    {
        return [
            'bc_id'    => $this->bonCommande->id,
            'numero'   => $this->bonCommande->numero,
            'montant'  => (float) $this->bonCommande->montant_total,
            'valide_par' => $this->valideParId,
        ];
    }
}