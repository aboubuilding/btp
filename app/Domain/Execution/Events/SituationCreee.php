<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\Situation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SituationCreee
{
    use Dispatchable, SerializesModels;

    public function __construct(public Situation $situation) {}

    public function toArray(): array
    {
        return [
            'situation_id'        => $this->situation->id,
            'projet_id'           => $this->situation->projet_id,
            'numero'              => $this->situation->numero,
            'montant_cumule_ht'   => (float) $this->situation->montant_cumule_ht,
            'montant_periode_ht'  => (float) $this->situation->montant_periode_ht,
            'net_a_payer'         => (float) $this->situation->net_a_payer,
        ];
    }
}