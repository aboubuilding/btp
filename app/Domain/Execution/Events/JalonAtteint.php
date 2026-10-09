<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\JalonProjet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JalonAtteint
{
    use Dispatchable, SerializesModels;

    public function __construct(public JalonProjet $jalon) {}

    public function toArray(): array
    {
        return [
            'jalon_id'      => $this->jalon->id,
            'projet_id'     => $this->jalon->projet_id,
            'libelle'       => $this->jalon->libelle,
            'date_echeance' => $this->jalon->date_echeance?->toDateString(),
            'date_atteinte' => $this->jalon->date_atteinte?->toDateString(),
        ];
    }
}