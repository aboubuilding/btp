<?php
namespace App\Listeners\Commercial;

use App\Domain\Commercial\Events\CautionExpireBientot;
use App\Domain\Socle\Models\User;
use App\Notifications\Commercial\CautionExpiranteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifierCautionExpirante implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(CautionExpireBientot $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['comptable', 'direction', 'directeur_technique'])
        )->where('est_actif', true)->get();

        if ($destinataires->isEmpty()) return;

        Notification::send($destinataires, new CautionExpiranteNotification($event->caution));

        Log::info('[Listener] Notification caution expirante', [
            'caution_id' => $event->caution->id,
        ]);
    }
}