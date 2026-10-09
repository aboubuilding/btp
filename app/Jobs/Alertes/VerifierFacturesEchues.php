<?php
namespace App\Jobs\Alertes;

use App\Domain\Finances\Models\Facture;
use App\Domain\Socle\Models\User;
use App\Notifications\FactureEchueNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Notification};

class VerifierFacturesEchues implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'alertes';

    public function __construct(
        public int $premierRappelJours = 1,
        public int $deuxiemeRappelJours = 15,
        public int $troisiemeRappelJours = 30,
    ) {}

    public function handle(): void
    {
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['comptable', 'direction', 'directeur_technique'])
        )->where('est_actif', true)->get();

        if ($destinataires->isEmpty()) return;

        $factures = Facture::with(['facturable', 'projet'])
            ->whereIn('statut', ['emise', 'partiellement_payee'])
            ->whereDate('date_echeance', '<', now())
            ->whereRaw('montant_paye < net_a_payer')
            ->where('etat', 1)
            ->get();

        foreach ($factures as $facture) {
            $joursRetard = (int) $facture->date_echeance->diffInDays(now());

            // Filtre : notifie uniquement aux paliers définis
            if (!$this->doitNotifier($joursRetard)) {
                continue;
            }

            Notification::send($destinataires, new FactureEchueNotification($facture));
        }

        Log::info('[Job] Vérification factures échues', ['total' => $factures->count()]);
    }

    private function doitNotifier(int $joursRetard): bool
    {
        return in_array($joursRetard, [
            $this->premierRappelJours,
            $this->deuxiemeRappelJours,
            $this->troisiemeRappelJours,
        ], true)
        || $joursRetard % 30 === 0; // Relance mensuelle après 30j
    }
}