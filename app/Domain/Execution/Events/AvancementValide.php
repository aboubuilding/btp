<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\AvancementProjet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AvancementValide
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public AvancementProjet $avancement,
        public int $valideParId,
    ) {}
}