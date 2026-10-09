<?php
namespace App\Domain\Finances\Events;

use App\Domain\Finances\Models\Paiement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaiementEnregistre
{
    use Dispatchable, SerializesModels;

    public function __construct(public Paiement $paiement) {}

    public function toArray(): array
    {
        return [
            'paiement_id'   => $this->paiement->id,
            'numero'        => $this->paiement->numero_paiement,
            'sens'          => $this->paiement->sens,
            'montant'       => (float) $this->paiement->montant,
            'mode'          => $this->paiement->mode,
            'date_paiement' => $this->paiement->date_paiement?->toDateString(),
            'id_payable'    => $this->paiement->id_payable,
        ];
    }
}