<?php
namespace App\Listeners\Commercial;

use App\Domain\Commercial\Events\DevisAccepte;
use App\Domain\Socle\Models\User;
use App\Notifications\Commercial\DevisAccepteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifierDirectionDevisAccepte implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(DevisAccepte $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'directeur_technique'])
        )->where('est_actif', true)->get();

        if ($destinataires->isEmpty()) return;

        Notification::send($destinataires, new DevisAccepteNotification($event->devis));

        Log::info('[Listener] Notification devis accepté', [
            'devis_id' => $event->devis->id,
            'nb_dest'  => $destinataires->count(),
        ]);
    }
}