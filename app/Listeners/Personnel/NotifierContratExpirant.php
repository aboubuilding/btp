<?php
namespace App\Listeners\Personnel;

use App\Domain\Personnel\Events\ContratExpireBientot;
use App\Domain\Socle\Models\User;
use App\Notifications\Personnel\ContratExpireNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierContratExpirant implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(ContratExpireBientot $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['rh', 'direction'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new ContratExpireNotification($event->contrat));
        }
    }
}