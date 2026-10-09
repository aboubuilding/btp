<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\Livraison;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LivraisonRefusee
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Livraison $livraison,
        public string $motif,
    ) {}
}