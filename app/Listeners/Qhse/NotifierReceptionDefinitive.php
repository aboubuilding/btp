<?php
namespace App\Listeners\Qhse;

use App\Domain\QHSE\Events\ReceptionDefinitiveSignee;
use App\Domain\Socle\Models\User;
use App\Notifications\QHSE\ReceptionDefinitiveSigneeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierReceptionDefinitive implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(ReceptionDefinitiveSignee $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'directeur_technique', 'comptable', 'responsable_qhse'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new ReceptionDefinitiveSigneeNotification($event->pv));
        }
    }
}