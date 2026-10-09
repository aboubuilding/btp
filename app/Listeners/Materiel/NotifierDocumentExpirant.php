<?php
namespace App\Listeners\Materiel;

use App\Domain\ParcMateriel\Events\DocumentBientotExpire;
use App\Domain\Socle\Models\User;
use App\Notifications\Socle\DocumentBientotExpireNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierDocumentExpirant implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(DocumentBientotExpire $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['responsable_materiel', 'responsable_qhse', 'directeur_technique'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new DocumentBientotExpireNotification($event->document));
        }
    }
}