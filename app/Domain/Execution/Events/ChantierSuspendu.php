<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\Projet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChantierSuspendu
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Projet $projet,
        public ?string $motif = null,
    ) {}
}