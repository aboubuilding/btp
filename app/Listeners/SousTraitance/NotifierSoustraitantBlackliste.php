<?php
namespace App\Listeners\SousTraitance;

use App\Domain\SousTraitance\Events\SoustraitantBlackliste;
use App\Domain\Socle\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifierSoustraitantBlackliste implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(SoustraitantBlackliste $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'directeur_technique', 'comptable'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new \App\Notifications\SousTraitance\SoustraitantBlacklisteNotification(
                $event->soustraitant,
                $event->motif
            ));
        }

        Log::warning('[Listener] Sous-traitant blacklisté', [
            'soustraitant_id' => $event->soustraitant->id,
            'motif'           => $event->motif,
        ]);
    }
}