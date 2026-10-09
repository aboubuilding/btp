<?php
namespace App\Listeners\Qhse;

use App\Domain\QHSE\Events\IncidentGraveDeclare;
use App\Domain\Socle\Models\User;
use App\Notifications\QHSE\IncidentGraveNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifierDirectionUrgence implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(IncidentGraveDeclare $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'directeur_technique', 'responsable_qhse'])
        )->where('est_actif', true)->get();

        if ($destinataires->isEmpty()) {
            Log::warning('[Listener] Aucun destinataire pour incident grave');
            return;
        }

        Notification::send($destinataires, new IncidentGraveNotification($event->incident));

        Log::critical('[Listener] Incident grave notifié', [
            'incident_id' => $event->incident->id,
            'gravite'     => $event->incident->gravite,
            'victimes'    => $event->incident->nombre_victimes,
            'nb_dest'     => $destinataires->count(),
        ]);
    }
}