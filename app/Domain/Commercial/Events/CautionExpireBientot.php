<?php
namespace App\Domain\Commercial\Events;

use App\Domain\Commercial\Models\CautionMarche;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CautionExpireBientot
{
    use Dispatchable, SerializesModels;

    public function __construct(public CautionMarche $caution) {}

    public function toArray(): array
    {
        return [
            'caution_id'   => $this->caution->id,
            'marche_id'    => $this->caution->marche_id,
            'type'         => $this->caution->type,
            'montant'      => (float) $this->caution->montant,
            'date_echeance'=> $this->caution->date_echeance?->toDateString(),
            'jours_restants' => $this->caution->date_echeance
                ? (int) now()->diffInDays($this->caution->date_echeance, false)
                : null,
        ];
    }
}