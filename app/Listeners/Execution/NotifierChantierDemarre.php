<?php
namespace App\Listeners\Execution;

use App\Domain\Execution\Events\ChantierDemarre;
use App\Domain\Socle\Models\User;
use App\Notifications\Execution\ChantierDemarreNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifierChantierDemarre implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(ChantierDemarre $event): void
    {
        $projet = $event->projet;

        // Notifie conducteur + chef + direction
        $employeIds = array_filter([
            $projet->conducteur_travaux_id,
            $projet->chef_chantier_id,
        ]);

        $destinataires = User::whereHas('employe', fn($q) => $q->whereIn('id', $employeIds))
            ->where('est_actif', true)->get();

        $direction = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'directeur_technique'])
        )->where('est_actif', true)->get();

        $tous = $destinataires->merge($direction)->unique('id');

        if ($tous->isNotEmpty()) {
            Notification::send($tous, new ChantierDemarreNotification($projet));
        }

        Log::info('[Listener] Notification chantier démarré', [
            'projet_id' => $projet->id,
        ]);
    }
}