<?php
namespace App\Jobs\Rapports;

use App\Domain\Execution\Models\Projet;
use App\Domain\Finances\Models\{Facture, Paiement};
use App\Domain\Personnel\Models\Presence;
use App\Domain\QHSE\Models\IncidentSecurite;
use App\Domain\Socle\Models\User;
use App\Domain\Socle\Services\CommunicationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererRapportHebdomadaire implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;
    public string $queue = 'rapports';

    public function handle(CommunicationService $communication): void
    {
        $debut = now()->subWeek()->startOfWeek();
        $fin   = now()->subWeek()->endOfWeek();

        $stats = [
            'semaine'             => $debut->format('d/m/Y') . ' au ' . $fin->format('d/m/Y'),
            'ca_facture'          => (float) Facture::whereBetween('date_facture', [$debut, $fin])
                ->where('type', 'client')->where('statut', '!=', 'annulee')->sum('montant_ttc'),
            'ca_encaisse'         => (float) Paiement::whereBetween('date_paiement', [$debut, $fin])
                ->where('sens', 'encaissement')->sum('montant'),
            'heures_travaillees'  => (float) Presence::whereBetween('date', [$debut, $fin])
                ->whereNotNull('valide_le')->sum('heures_travaillees'),
            'incidents'           => IncidentSecurite::whereBetween('date_incident', [$debut, $fin])->count(),
            'chantiers_actifs'    => Projet::where('statut', 'en_cours')->count(),
            'top_chantiers'       => Projet::where('statut', 'en_cours')
                ->orderByDesc('pourcentage_avancement')
                ->limit(5)
                ->get(['code', 'nom', 'pourcentage_avancement']),
        ];

        $corps = view('emails.rapports.hebdomadaire', compact('stats', 'debut', 'fin'))->render();

        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'directeur_technique'])
        )->where('est_actif', true)
         ->pluck('email')
         ->filter()
         ->map(fn($email) => ['email' => $email])
         ->toArray();

        if (!empty($destinataires)) {
            $communication->envoyerDepuisTemplate(
                'rapport.hebdomadaire',
                ['semaine' => $stats['semaine'], 'contenu' => $corps],
                $destinataires
            );
        }

        Log::info('[Job] Rapport hebdomadaire généré', $stats);
    }
}