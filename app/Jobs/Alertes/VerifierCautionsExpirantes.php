<?php
namespace App\Jobs\Alertes;

use App\Domain\Commercial\Models\CautionMarche;
use App\Domain\Socle\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Notification};

class VerifierCautionsExpirantes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'alertes';

    public function __construct(public int $joursAvant = 30) {}

    public function handle(): void
    {
        $cautions = CautionMarche::with('marche')
            ->where('statut', 'active')
            ->whereNotNull('date_echeance')
            ->whereDate('date_echeance', '>=', now())
            ->whereDate('date_echeance', '<=', now()->addDays($this->joursAvant))
            ->get();

        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['comptable', 'direction', 'directeur_technique'])
        )->where('est_actif', true)->get();

        foreach ($cautions as $caution) {
            $notification = new \App\Notifications\CautionExpiranteNotification($caution);

            if ($destinataires->isNotEmpty()) {
                Notification::send($destinataires, $notification);
            }
        }

        Log::info('[Job] Vérification cautions', ['total' => $cautions->count()]);
    }
}