<?php
namespace App\Domain\Commercial\Events;

use App\Domain\Commercial\Models\Devis;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DevisAccepte
{
    use Dispatchable, SerializesModels;

    public function __construct(public Devis $devis) {}

    public function toArray(): array
    {
        return [
            'devis_id'      => $this->devis->id,
            'numero'        => $this->devis->numero,
            'client_id'     => $this->devis->client_id,
            'client_nom'    => $this->devis->client?->nom,
            'objet'         => $this->devis->objet,
            'montant_ht'    => (float) $this->devis->montant_ht,
            'montant_ttc'   => (float) $this->devis->montant_ttc,
            'marge_prevue'  => (float) $this->devis->marge_previsionnelle,
            'taux_marge'    => $this->devis->taux_marge,
            'accepte_le'    => now()->toIso8601String(),
        ];
    }
}