<?php
namespace App\Domain\Finances\Events;

use App\Domain\Finances\Models\EcritureComptable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EcritureValidee
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public EcritureComptable $ecriture,
        public int $valideParId,
    ) {}
}