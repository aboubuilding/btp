<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\Inventaire;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventaireCree
{
    use Dispatchable, SerializesModels;

    public function __construct(public Inventaire $inventaire) {}
}