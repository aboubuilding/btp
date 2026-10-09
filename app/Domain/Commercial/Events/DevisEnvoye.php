<?php
namespace App\Domain\Commercial\Events;

use App\Domain\Commercial\Models\Devis;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DevisEnvoye
{
    use Dispatchable, SerializesModels;

    public function __construct(public Devis $devis) {}

    public function toArray(): array
    {
        return [
            'devis_id' => $this->devis->id,
            'numero'   => $this->devis->numero,
            'client'   => $this->devis->client?->nom,
            'envoye_le'=> now()->toIso8601String(),
        ];
    }
}