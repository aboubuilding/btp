<?php
namespace App\Listeners\Finances;

use App\Domain\Finances\Events\ExerciceCloture;
use App\Domain\Socle\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierExerciceCloture implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(ExerciceCloture $event): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'comptable'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new \App\Notifications\Finances\ExerciceClotureNotification($event->exercice));
        }
    }
}