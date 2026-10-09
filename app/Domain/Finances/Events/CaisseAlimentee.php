<?php
namespace App\Domain\Finances\Events;

use App\Domain\Finances\Models\Caisse;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CaisseAlimentee
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Caisse $caisse,
        public float $montant,
        public float $nouveauSolde,
    ) {}
}