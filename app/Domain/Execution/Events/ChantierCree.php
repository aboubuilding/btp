<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\Projet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChantierCree
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Projet $projet,
        public ?int $creeParId = null,
    ) {}

    public function toArray(): array
    {
        return [
            'projet_id'    => $this->projet->id,
            'code'         => $this->projet->code,
            'nom'          => $this->projet->nom,
            'client_id'    => $this->projet->client_id,
            'marche_id'    => $this->projet->marche_id,
            'type'         => $this->projet->type,
            'montant'      => (float) $this->projet->montant_contrat,
            'date_debut'   => $this->projet->date_debut_prevue?->toDateString(),
            'date_fin'     => $this->projet->date_fin_prevue?->toDateString(),
            'cree_par'     => $this->creeParId,
        ];
    }
}