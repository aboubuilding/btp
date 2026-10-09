<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\Situation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SituationApprouvee
{
    use Dispatchable, SerializesModels;

    public function __construct(public Situation $situation) {}

    public function toArray(): array
    {
        return [
            'situation_id'    => $this->situation->id,
            'projet_id'       => $this->situation->projet_id,
            'code_chantier'   => $this->situation->projet?->code,
            'numero'          => $this->situation->numero,
            'montant_periode' => (float) $this->situation->montant_periode_ht,
            'retenue'         => (float) $this->situation->retenue_garantie,
            'remb_avance'     => (float) $this->situation->remboursement_avance,
            'net_a_payer'     => (float) $this->situation->net_a_payer,
            'approuvee_le'    => now()->toIso8601String(),
        ];
    }
}