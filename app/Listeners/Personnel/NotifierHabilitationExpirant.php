<?php
namespace App\Listeners\Personnel;

use App\Domain\Personnel\Events\HabilitationExpire;
use App\Domain\Socle\Models\User;
use App\Notifications\Personnel\HabilitationExpireNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierHabilitationExpirant implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(HabilitationExpire $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['rh', 'responsable_qhse'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new HabilitationExpireNotification($event->document));
        }
    }
}