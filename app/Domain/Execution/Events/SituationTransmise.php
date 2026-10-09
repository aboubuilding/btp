<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\Situation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SituationTransmise
{
    use Dispatchable, SerializesModels;

    public function __construct(public Situation $situation) {}
}