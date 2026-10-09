<?php
namespace App\Listeners\Materiel;

use App\Domain\ParcMateriel\Events\PanneDeclaree;
use App\Domain\Socle\Models\User;
use App\Notifications\ParcMateriel\PanneDeclareeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifierResponsableMateriel implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(PanneDeclaree $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['responsable_materiel', 'directeur_technique'])
        )->where('est_actif', true)->get();

        if ($destinataires->isEmpty()) return;

        Notification::send($destinataires, new PanneDeclareeNotification($event->panne));

        Log::info('[Listener] Notification panne déclarée', [
            'panne_id' => $event->panne->id,
        ]);
    }
}