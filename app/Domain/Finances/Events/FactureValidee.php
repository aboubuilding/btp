<?php
namespace App\Domain\Finances\Events;

use App\Domain\Finances\Models\Facture;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FactureValidee
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Facture $facture,
        public int $valideParId,
    ) {}
}