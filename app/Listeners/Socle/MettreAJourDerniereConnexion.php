<?php
namespace App\Listeners\Socle;

use App\Domain\Socle\Events\UtilisateurConnecte;
use App\Domain\Socle\Repositories\UserRepositoryInterface;

class MettreAJourDerniereConnexion
{
    public function __construct(private UserRepositoryInterface $users) {}

    public function handle(UtilisateurConnecte $event): void
    {
        $this->users->enregistrerConnexion($event->user, $event->ip);
    }
}