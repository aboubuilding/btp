<?php
namespace App\Listeners\Execution;

use App\Domain\Execution\Events\JalonsManque;
use App\Domain\Socle\Models\User;
use App\Notifications\Execution\JalonsManqueNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifierJalonManque implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(JalonsManque $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['directeur_technique', 'direction'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new JalonsManqueNotification($event->jalon));
        }

        Log::warning('[Listener] Jalon manqué notifié', [
            'jalon_id' => $event->jalon->id,
        ]);
    }
}