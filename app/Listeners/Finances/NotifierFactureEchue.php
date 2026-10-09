<?php
namespace App\Listeners\Finances;

use App\Domain\Finances\Events\FactureEchue;
use App\Domain\Socle\Models\User;
use App\Notifications\Finances\FactureEchueNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierFactureEchue implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(FactureEchue $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['comptable', 'direction', 'directeur_technique'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new FactureEchueNotification($event->facture));
        }
    }
}