<?php
namespace App\Domain\Finances\Events;

use App\Domain\Finances\Models\Facture;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FacturePayee
{
    use Dispatchable, SerializesModels;

    public function __construct(public Facture $facture) {}

    public function toArray(): array
    {
        return [
            'facture_id'   => $this->facture->id,
            'numero'       => $this->facture->numero_facture,
            'montant_paye' => (float) $this->facture->montant_paye,
        ];
    }
}