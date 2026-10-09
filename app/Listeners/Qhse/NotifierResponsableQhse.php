<?php
namespace App\Listeners\Qhse;

use App\Domain\QHSE\Events\IncidentGraveDeclare;
use App\Domain\Socle\Models\User;
use App\Notifications\QHSE\IncidentGraveNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierResponsableQhse implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(IncidentGraveDeclare $event): void
    {
        $qhs = User::whereHas('role', fn($q) =>
            $q->where('slug', 'responsable_qhse')
        )->where('est_actif', true)->get();

        if ($qhs->isNotEmpty()) {
            Notification::send($qhs, new IncidentGraveNotification($event->incident));
        }
    }
}