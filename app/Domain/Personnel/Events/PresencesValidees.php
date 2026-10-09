<?php
namespace App\Domain\Personnel\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresencesValidees
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $projetId,
        public string $date,
        public int $nombreValidees,
        public int $valideParId,
    ) {}

    public function toArray(): array
    {
        return [
            'projet_id'      => $this->projetId,
            'date'           => $this->date,
            'nombre_validees'=> $this->nombreValidees,
            'valide_par'     => $this->valideParId,
        ];
    }
}