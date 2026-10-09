<?php
namespace App\Listeners\Finances;

use App\Domain\Finances\Events\PaiementEnregistre;
use App\Domain\Socle\Models\User;
use App\Notifications\Finances\PaiementEnregistreNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierPaiementEnregistre implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(PaiementEnregistre $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['comptable', 'direction'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new PaiementEnregistreNotification($event->paiement));
        }
    }
}