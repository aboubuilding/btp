<?php
namespace App\Listeners\SousTraitance;

use App\Domain\SousTraitance\Events\PaiementPlafondAtteint;
use App\Domain\Socle\Models\User;
use App\Notifications\SousTraitance\PaiementPlafondAtteintNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierPlafondAtteint implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(PaiementPlafondAtteint $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['comptable', 'direction', 'directeur_technique'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new PaiementPlafondAtteintNotification($event->contrat));
        }
    }
}