<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\AvanceSalaire;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AvanceDemandee
{
    use Dispatchable, SerializesModels;

    public function __construct(public AvanceSalaire $avance) {}

    public function toArray(): array
    {
        return [
            'avance_id'   => $this->avance->id,
            'employe_id'  => $this->avance->employee_id,
            'employe_nom' => $this->avance->employe?->nom_complet,
            'montant'     => (float) $this->avance->montant,
            'date'        => $this->avance->date_avance?->toDateString(),
        ];
    }
}