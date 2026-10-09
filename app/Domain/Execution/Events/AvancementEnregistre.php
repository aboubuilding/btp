<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\AvancementProjet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AvancementEnregistre
{
    use Dispatchable, SerializesModels;

    public function __construct(public AvancementProjet $avancement) {}
}