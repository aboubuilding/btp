<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\Employe;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeCree
{
    use Dispatchable, SerializesModels;

    public function __construct(public Employe $employe) {}

    public function toArray(): array
    {
        return [
            'employe_id'   => $this->employe->id,
            'matricule'    => $this->employe->matricule,
            'nom_complet'  => $this->employe->nom_complet,
            'type_contrat' => $this->employe->type_contrat,
            'poste'        => $this->employe->poste?->nom,
            'departement'  => $this->employe->departement?->nom,
        ];
    }
}