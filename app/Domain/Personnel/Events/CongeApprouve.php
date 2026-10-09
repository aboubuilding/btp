<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\DemandeConge;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CongeApprouve
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public DemandeConge $conge,
        public int $approuveParId,
    ) {}
}