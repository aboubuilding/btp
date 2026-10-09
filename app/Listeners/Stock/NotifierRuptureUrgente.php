<?php
namespace App\Listeners\Stock;

use App\Domain\Approvisionnement\Events\StockRupture;
use App\Domain\Socle\Models\User;
use App\Notifications\Approvisionnement\StockSousSeuilNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierRuptureUrgente implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(StockRupture $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['responsable_achat', 'direction'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new StockSousSeuilNotification($event->materiau, $event->niveau));
        }
    }
}