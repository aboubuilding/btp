<?php
namespace App\Domain\Finances\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeuilValidationAtteint
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $type,        // 'bon_commande', 'situation', 'depense', 'paiement'
        public mixed $objet,
        public float $montant,
        public float $seuil,
    ) {}

    public function toArray(): array
    {
        return [
            'type'    => $this->type,
            'montant' => $this->montant,
            'seuil'   => $this->seuil,
        ];
    }
}