<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\DemandeAchat;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DemandeAchatRejetee
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public DemandeAchat $demande,
        public int $rejeteParId,
        public string $motif,
    ) {}
}