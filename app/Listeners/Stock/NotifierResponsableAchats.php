<?php
namespace App\Listeners\Stock;

use App\Domain\Approvisionnement\Events\StockSousSeuil;
use App\Domain\Socle\Models\User;
use App\Notifications\Approvisionnement\StockSousSeuilNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifierResponsableAchats implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(StockSousSeuil $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['responsable_achat', 'direction', 'directeur_technique', 'magasinier'])
        )->where('est_actif', true)->get();

        if ($destinataires->isEmpty()) return;

        Notification::send($destinataires, new StockSousSeuilNotification($event->materiau, $event->niveau));

        Log::info('[Listener] Notification stock sous seuil', [
            'materiau_id' => $event->materiau->id,
        ]);
    }
}