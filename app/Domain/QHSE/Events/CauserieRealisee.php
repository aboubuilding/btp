<?php
namespace App\Domain\QHSE\Events;

use App\Domain\QHSE\Models\CauserieSecurite;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CauserieRealisee
{
    use Dispatchable, SerializesModels;

    public function __construct(public CauserieSecurite $causerie) {}
}