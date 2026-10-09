<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\Tache;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TacheTerminee
{
    use Dispatchable, SerializesModels;

    public function __construct(public Tache $tache) {}

    public function toArray(): array
    {
        return [
            'tache_id'   => $this->tache->id,
            'nom'        => $this->tache->nom,
            'projet_id'  => $this->tache->projet_id,
            'assigne_a'  => $this->tache->assigne?->nom_complet,
            'date_fin'   => $this->tache->date_fin?->toDateString(),
        ];
    }
}