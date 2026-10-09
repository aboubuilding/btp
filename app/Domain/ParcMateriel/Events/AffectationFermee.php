<?php
namespace App\Domain\ParcMateriel\Events;

use App\Domain\ParcMateriel\Models\AffectationEquipement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AffectationFermee
{
    use Dispatchable, SerializesModels;

    public function __construct(public AffectationEquipement $affectation) {}
}