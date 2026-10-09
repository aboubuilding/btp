<?php
namespace App\Listeners\Socle;

use App\Domain\Socle\Events\ParametreModifie;
use App\Domain\Socle\Services\ParametreService;

class InvaliderCacheParametres
{
    public function __construct(private ParametreService $parametres) {}

    public function handle(ParametreModifie $event): void
    {
        $this->parametres->oublier($event->parametre->cle);
    }
}