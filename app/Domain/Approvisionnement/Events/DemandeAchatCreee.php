<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\DemandeAchat;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DemandeAchatCreee
{
    use Dispatchable, SerializesModels;

    public function __construct(public DemandeAchat $demande) {}

    public function toArray(): array
    {
        return [
            'demande_id'    => $this->demande->id,
            'numero'        => $this->demande->numero,
            'projet_id'     => $this->demande->projet_id,
            'demandeur'     => $this->demande->demandeur?->nom_complet,
            'nb_articles'   => $this->demande->articles->count(),
        ];
    }
}