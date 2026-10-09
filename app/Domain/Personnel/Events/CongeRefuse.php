<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\DemandeConge;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CongeRefuse
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public DemandeConge $conge,
        public int $refuseParId,
        public ?string $motif = null,
    ) {}
}