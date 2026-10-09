<?php
namespace App\Listeners\Commercial;

use App\Domain\Commercial\Events\MarcheSigne;
use App\Domain\Socle\Models\User;
use App\Notifications\Commercial\MarcheSigneNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifierMarcheSigne implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(MarcheSigne $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'directeur_technique', 'conducteur_travaux', 'comptable'])
        )->where('est_actif', true)->get();

        if ($destinataires->isEmpty()) return;

        Notification::send($destinataires, new MarcheSigneNotification($event->marche));

        Log::info('[Listener] Notification marché signé', [
            'marche_id' => $event->marche->id,
            'nb_dest'   => $destinataires->count(),
        ]);
    }
}