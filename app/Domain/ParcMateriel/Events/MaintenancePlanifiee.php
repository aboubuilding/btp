<?php
namespace App\Domain\ParcMateriel\Events;

use App\Domain\ParcMateriel\Models\MaintenanceEquipement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaintenancePlanifiee
{
    use Dispatchable, SerializesModels;

    public function __construct(public MaintenanceEquipement $maintenance) {}
}