<?php
namespace App\Jobs\Alertes;

use App\Domain\Personnel\Models\Contrat;
use App\Domain\Socle\Models\User;
use App\Notifications\ContratExpireNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Notification};

class VerifierContratsEcheance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'alertes';

    public function __construct(public int $joursAvant = 30) {}

    public function handle(): void
    {
        $contrats = Contrat::with('employe')
            ->where('statut', 'en_cours')
            ->whereNotNull('date_fin')
            ->whereDate('date_fin', '>=', now())
            ->whereDate('date_fin', '<=', now()->addDays($this->joursAvant))
            ->where('etat', 1)
            ->get();

        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['rh', 'direction'])
        )->where('est_actif', true)->get();

        foreach ($contrats as $contrat) {
            if ($destinataires->isNotEmpty()) {
                Notification::send($destinataires, new ContratExpireNotification($contrat));
            }
        }

        Log::info('[Job] Vérification contrats', ['total' => $contrats->count()]);
    }
}