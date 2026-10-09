<?php
namespace App\Listeners\Approvisionnement;

use App\Domain\Approvisionnement\Events\DemandeAchatCreee;
use App\Domain\Socle\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierResponsablesValidation implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(DemandeAchatCreee $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['directeur_technique', 'direction'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new \App\Notifications\Approvisionnement\DemandeAchatEnAttenteNotification($event->demande));
        }
    }
}