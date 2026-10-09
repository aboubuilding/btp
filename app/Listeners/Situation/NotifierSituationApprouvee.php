<?php
namespace App\Listeners\Situation;

use App\Domain\Execution\Events\SituationApprouvee;
use App\Domain\Socle\Models\User;
use App\Notifications\Execution\SituationApprouveeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierSituationApprouvee implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(SituationApprouvee $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['comptable', 'direction', 'directeur_technique'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new SituationApprouveeNotification($event->situation));
        }
    }
}