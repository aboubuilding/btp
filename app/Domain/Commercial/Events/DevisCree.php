<?php
namespace App\Domain\Commercial\Events;

use App\Domain\Commercial\Models\Devis;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DevisCree
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Devis $devis,
        public ?int $creeParId = null,
    ) {}

    public function toArray(): array
    {
        return [
            'devis_id'    => $this->devis->id,
            'numero'      => $this->devis->numero,
            'client_id'   => $this->devis->client_id,
            'montant_ht'  => (float) $this->devis->montant_ht,
            'montant_ttc' => (float) $this->devis->montant_ttc,
            'cree_par'    => $this->creeParId,
        ];
    }
}