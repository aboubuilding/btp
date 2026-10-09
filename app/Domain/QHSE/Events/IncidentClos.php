<?php
namespace App\Domain\QHSE\Events;

use App\Domain\QHSE\Models\IncidentSecurite;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidentClos
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public IncidentSecurite $incident,
        public int $closParId,
    ) {}
}