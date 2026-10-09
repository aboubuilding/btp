<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\BonCommande;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BonCommandeAnnule
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public BonCommande $bonCommande,
        public ?string $motif = null,
    ) {}
}