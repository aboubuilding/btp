<?php
namespace App\Domain\ParcMateriel\Events;

use App\Domain\ParcMateriel\Models\ReleveCarburant;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReleveCarburantEnregistre
{
    use Dispatchable, SerializesModels;

    public function __construct(public ReleveCarburant $releve) {}
}