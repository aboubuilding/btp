<?php
namespace App\Listeners\Qhse;

use App\Domain\QHSE\Events\ReserveOuverte;
use App\Domain\Socle\Models\User;
use App\Notifications\QHSE\ReserveOuverteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierReserveOuverte implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(ReserveOuverte $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['responsable_qhse', 'conducteur_travaux', 'directeur_technique'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new ReserveOuverteNotification($event->reserve));
        }
    }
}