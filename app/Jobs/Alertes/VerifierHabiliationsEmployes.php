<?php
namespace App\Jobs\Alertes;

use App\Domain\Personnel\Models\DocumentEmploye;
use App\Domain\Socle\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Notification};

class VerifierHabiliationsEmployes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'alertes';

    public function handle(): void
    {
        $habilitations = DocumentEmploye::with('employe')
            ->where('type', 'habilitation')
            ->whereNotNull('date_expiration')
            ->whereDate('date_expiration', '>=', now())
            ->whereDate('date_expiration', '<=', now()->addDays(60))
            ->get();

        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['rh', 'responsable_qhse'])
        )->where('est_actif', true)->get();

        foreach ($habilitations as $habilitation) {
            $notification = new \App\Notifications\HabilitationExpireNotification($habilitation);

            if ($destinataires->isNotEmpty()) {
                Notification::send($destinataires, $notification);
            }
        }

        Log::info('[Job] Vérification habilitations', ['total' => $habilitations->count()]);
    }
}