<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\Projet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class TacheEnRetard
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Projet $projet,
        public Collection $taches,
    ) {}

    public function toArray(): array
    {
        return [
            'projet_id'    => $this->projet->id,
            'code'         => $this->projet->code,
            'nb_taches'    => $this->taches->count(),
            'taches'       => $this->taches->map(fn($t) => [
                'id'       => $t->id,
                'nom'      => $t->nom,
                'date_fin' => $t->date_fin?->toDateString(),
                'retard'   => $t->date_fin ? (int) $t->date_fin->diffInDays(now()) : 0,
            ])->toArray(),
        ];
    }
}