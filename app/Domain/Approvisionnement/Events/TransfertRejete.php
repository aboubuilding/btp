<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\TransfertStock;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransfertRejete
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public TransfertStock $transfert,
        public int $rejeteParId,
        public ?string $motif = null,
    ) {}
}