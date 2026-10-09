<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\Projet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChantierDemarre
{
    use Dispatchable, SerializesModels;

    public function __construct(public Projet $projet) {}

    public function toArray(): array
    {
        return [
            'projet_id'         => $this->projet->id,
            'code'              => $this->projet->code,
            'nom'               => $this->projet->nom,
            'conducteur'        => $this->projet->conducteur?->nom_complet,
            'chef_chantier'     => $this->projet->chefChantier?->nom_complet,
            'date_debut_reelle' => $this->projet->date_debut_reelle?->toDateString(),
        ];
    }
}