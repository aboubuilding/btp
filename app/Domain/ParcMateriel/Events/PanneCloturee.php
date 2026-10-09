<?php
namespace App\Domain\ParcMateriel\Events;

use App\Domain\ParcMateriel\Models\PanneEquipement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PanneCloturee
{
    use Dispatchable, SerializesModels;

    public function __construct(public PanneEquipement $panne) {}
}