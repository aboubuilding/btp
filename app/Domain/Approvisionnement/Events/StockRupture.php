<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\{Materiau, NiveauStock};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockRupture
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Materiau $materiau,
        public NiveauStock $niveau,
    ) {}
}