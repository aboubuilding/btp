<?php
namespace App\Jobs\Alertes;

use App\Domain\Execution\Models\JalonProjet;
use App\Domain\Socle\Models\User;
use App\Notifications\JalonsManqueNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Notification};

class VerifierJalonsManques implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'alertes';

    public function handle(): void
    {
        $jalons = JalonProjet::with('projet')
            ->whereNull('date_atteinte')
            ->whereDate('date_echeance', '<', now())
            ->where('etat', 1)
            ->get();

        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['directeur_technique', 'direction'])
        )->where('est_actif', true)->get();

        foreach ($jalons as $jalon) {
            if ($destinataires->isNotEmpty()) {
                Notification::send($destinataires, new JalonsManqueNotification($jalon));
            }
        }

        Log::info('[Job] Vérification jalons manqués', ['total' => $jalons->count()]);
    }
}