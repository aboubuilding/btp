<?php
namespace App\Listeners\Execution;

use App\Domain\Execution\Events\TacheEnRetard;
use App\Domain\Socle\Models\User;
use App\Notifications\Execution\TacheEnRetardNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifierTachesEnRetard implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(TacheEnRetard $event): void
    {
        $projet = $event->projet;

        $employeIds = array_filter([
            $projet->conducteur_travaux_id,
            $projet->chef_chantier_id,
        ]);

        $destinataires = User::whereHas('employe', fn($q) => $q->whereIn('id', $employeIds))
            ->where('est_actif', true)->get();

        $direction = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['directeur_technique'])
        )->where('est_actif', true)->get();

        $tous = $destinataires->merge($direction)->unique('id');

        if ($tous->isNotEmpty()) {
            Notification::send($tous, new TacheEnRetardNotification($projet, $event->taches));
        }

        Log::info('[Listener] Notification tâches en retard', [
            'projet_id' => $projet->id,
            'nb_taches' => $event->taches->count(),
        ]);
    }
}