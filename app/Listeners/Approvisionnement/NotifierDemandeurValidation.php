<?php
namespace App\Listeners\Approvisionnement;

use App\Domain\Approvisionnement\Events\DemandeAchatValidee;
use App\Domain\Socle\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierDemandeurValidation implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(DemandeAchatValidee $event): void
    {
        $demandeur = User::whereHas('employe', fn($q) =>
            $q->where('id', $event->demande->demandeur_id)
        )->first();

        if ($demandeur) {
            $demandeur->notify(new \App\Notifications\Approvisionnement\DemandeAchatValideeNotification($event->demande));
        }
    }
}