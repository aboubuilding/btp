<?php
namespace App\Domain\Commercial\Events;

use App\Domain\Commercial\Models\Devis;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DevisRefuse
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Devis $devis,
        public ?string $motif = null,
    ) {}
}