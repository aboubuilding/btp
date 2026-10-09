<?php
namespace App\Domain\Commercial\Events;

use App\Domain\Commercial\Models\Marche;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MarcheSigne
{
    use Dispatchable, SerializesModels;

    public function __construct(public Marche $marche) {}

    public function toArray(): array
    {
        return [
            'marche_id'       => $this->marche->id,
            'reference'       => $this->marche->reference,
            'client_id'       => $this->marche->client_id,
            'client_nom'      => $this->marche->client?->nom,
            'objet'           => $this->marche->objet,
            'montant_initial' => (float) $this->marche->montant_initial,
            'delai_jours'     => $this->marche->delai_contractuel_jours,
            'taux_avance'     => (float) $this->marche->taux_avance,
            'retenue_garantie'=> (float) $this->marche->taux_retenue_garantie,
            'date_signature'  => $this->marche->date_signature?->toDateString(),
        ];
    }
}