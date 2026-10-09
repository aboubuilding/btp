<?php
namespace App\Domain\SousTraitance\Events;

use App\Domain\SousTraitance\Models\ContratSousTraitant;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContratSousTraitantResilie
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ContratSousTraitant $contrat,
        public ?string $motif = null,
    ) {}
}