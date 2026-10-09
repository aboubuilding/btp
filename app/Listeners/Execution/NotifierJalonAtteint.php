<?php
namespace App\Listeners\Execution;

use App\Domain\Execution\Events\JalonAtteint;
use App\Domain\Socle\Models\User;
use App\Notifications\Execution\JalonsAtteintNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierJalonAtteint implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(JalonAtteint $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'directeur_technique', 'conducteur_travaux'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new JalonsAtteintNotification($event->jalon));
        }
    }
}