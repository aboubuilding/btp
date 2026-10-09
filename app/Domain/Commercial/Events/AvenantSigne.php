<?php
namespace App\Domain\Commercial\Events;

use App\Domain\Commercial\Models\AvenantMarche;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AvenantSigne
{
    use Dispatchable, SerializesModels;

    public function __construct(public AvenantMarche $avenant) {}

    public function toArray(): array
    {
        return [
            'avenant_id'   => $this->avenant->id,
            'marche_id'    => $this->avenant->marche_id,
            'numero'       => $this->avenant->numero,
            'objet'        => $this->avenant->objet,
            'montant'      => (float) $this->avenant->montant,
            'delai_jours'  => $this->avenant->delai_jours,
            'date_signature' => $this->avenant->date_signature?->toDateString(),
        ];
    }
}