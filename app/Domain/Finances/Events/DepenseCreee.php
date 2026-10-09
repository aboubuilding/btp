<?php
namespace App\Domain\Finances\Events;

use App\Domain\Finances\Models\Depense;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DepenseCreee
{
    use Dispatchable, SerializesModels;

    public function __construct(public Depense $depense) {}

    public function toArray(): array
    {
        return [
            'depense_id'  => $this->depense->id,
            'projet_id'   => $this->depense->projet_id,
            'categorie'   => $this->depense->categorie,
            'montant'     => (float) $this->depense->montant,
            'date'        => $this->depense->date_depense?->toDateString(),
            'justificatif'=> $this->depense->est_justifiee,
        ];
    }
}