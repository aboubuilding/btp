<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\Projet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChantierTermine
{
    use Dispatchable, SerializesModels;

    public function __construct(public Projet $projet) {}

    public function toArray(): array
    {
        return [
            'projet_id'   => $this->projet->id,
            'code'        => $this->projet->code,
            'date_fin'    => $this->projet->date_fin_reelle?->toDateString(),
            'budget_prevu'=> (float) $this->projet->budget_prevu,
            'budget_reel' => (float) $this->projet->budget_reel,
            'ecart'       => (float) $this->projet->ecart_budget,
        ];
    }
}