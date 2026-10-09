<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\PeriodePaie;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BulletinsGeneres
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public PeriodePaie $periode,
        public int $nombreBulletins,
    ) {}

    public function toArray(): array
    {
        return [
            'periode_id'      => $this->periode->id,
            'libelle'         => $this->periode->libelle,
            'nombre_bulletins'=> $this->nombreBulletins,
            'total_brut'      => $this->periode->total_brut,
            'total_net'       => $this->periode->total_net,
        ];
    }
}