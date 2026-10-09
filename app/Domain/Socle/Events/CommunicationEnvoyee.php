<?php
namespace App\Domain\Socle\Events;

use App\Domain\Socle\Models\Communication;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommunicationEnvoyee
{
    use Dispatchable, SerializesModels;

    public function __construct(public Communication $communication) {}

    public function toArray(): array
    {
        return [
            'communication_id' => $this->communication->id,
            'sujet'            => $this->communication->sujet,
            'destinataires'    => $this->communication->nb_destinataires,
            'envoye_le'        => $this->communication->envoye_le?->toIso8601String(),
        ];
    }
}