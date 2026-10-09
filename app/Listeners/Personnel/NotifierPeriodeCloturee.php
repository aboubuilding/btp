<?php
namespace App\Listeners\Personnel;

use App\Domain\Personnel\Events\PeriodePaieCloturee;
use App\Domain\Socle\Models\User;
use App\Notifications\Personnel\PeriodePaieClotureeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierPeriodeCloturee implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(PeriodePaieCloturee $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'comptable', 'rh'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new PeriodePaieClotureeNotification($event->periode));
        }
    }
}