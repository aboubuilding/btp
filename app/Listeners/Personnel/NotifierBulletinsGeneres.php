<?php
namespace App\Listeners\Personnel;

use App\Domain\Personnel\Events\BulletinsGeneres;
use App\Domain\Socle\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierBulletinsGeneres implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(BulletinsGeneres $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['rh', 'comptable', 'direction'])
        )->where('est_actif', true)->get();

        if ($destinataires->isEmpty()) return;

        Notification::send($destinataires, new \App\Notifications\Personnel\BulletinsGeneresNotification(
            $event->periode,
            $event->nombreBulletins
        ));
    }
}