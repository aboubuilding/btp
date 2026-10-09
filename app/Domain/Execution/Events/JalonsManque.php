<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\JalonProjet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JalonsManque
{
    use Dispatchable, SerializesModels;

    public function __construct(public JalonProjet $jalon) {}

    public function toArray(): array
    {
        return [
            'jalon_id'      => $this->jalon->id,
            'projet_id'     => $this->jalon->projet_id,
            'code_chantier' => $this->jalon->projet?->code,
            'libelle'       => $this->jalon->libelle,
            'date_echeance' => $this->jalon->date_echeance?->toDateString(),
            'retard_jours'  => (int) now()->diffInDays($this->jalon->date_echeance),
        ];
    }
}