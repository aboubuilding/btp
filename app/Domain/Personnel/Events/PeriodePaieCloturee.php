<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\PeriodePaie;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PeriodePaieCloturee
{
    use Dispatchable, SerializesModels;

    public function __construct(public PeriodePaie $periode) {}

    public function toArray(): array
    {
        return [
            'periode_id'   => $this->periode->id,
            'libelle'      => $this->periode->libelle,
            'type'         => $this->periode->type,
            'date_debut'   => $this->periode->date_debut?->toDateString(),
            'date_fin'     => $this->periode->date_fin?->toDateString(),
            'total_brut'   => $this->periode->total_brut,
            'total_net'    => $this->periode->total_net,
            'nb_bulletins' => $this->periode->bulletins()->count(),
            'cloture_le'   => $this->periode->cloture_le?->toIso8601String(),
        ];
    }
}