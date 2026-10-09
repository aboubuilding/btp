<?php
namespace App\Domain\ParcMateriel\Events;

use App\Domain\ParcMateriel\Models\Equipement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EquipementCree
{
    use Dispatchable, SerializesModels;

    public function __construct(public Equipement $equipement) {}
}