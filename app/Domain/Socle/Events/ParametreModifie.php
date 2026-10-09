<?php
namespace App\Domain\Socle\Events;

use App\Domain\Socle\Models\Parametre;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParametreModifie
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Parametre $parametre,
        public ?string $ancienneValeur = null,
        public ?string $nouvelleValeur = null,
    ) {}
}