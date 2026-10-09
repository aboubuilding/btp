<?php
namespace App\Listeners\Socle;

use App\Domain\Socle\Events\CommunicationEchouee;
use App\Domain\Socle\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifierAdminEchec implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(CommunicationEchouee $event): void
    {
        $admins = User::whereHas('role', fn($q) => $q->where('slug', 'admin'))
            ->where('est_actif', true)
            ->get();

        if ($admins->isEmpty()) return;

        Notification::send($admins, new \App\Notifications\Socle\CommunicationEchoueeNotification(
            $event->communication,
            $event->erreur
        ));

        Log::warning('[Listener] Communication échouée notifiée aux admins', [
            'communication_id' => $event->communication->id,
        ]);
    }
}