<?php
namespace App\Listeners\Personnel;

use App\Domain\Personnel\Events\CongeDemande;
use App\Domain\Socle\Models\User;
use App\Notifications\Personnel\CongeDemandeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierDemandeConge implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(CongeDemande $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['rh', 'directeur_technique'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new CongeDemandeNotification($event->conge));
        }
    }
}