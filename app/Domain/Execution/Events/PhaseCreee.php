<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\PhaseProjet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PhaseCreee
{
    use Dispatchable, SerializesModels;

    public function __construct(public PhaseProjet $phase) {}
}