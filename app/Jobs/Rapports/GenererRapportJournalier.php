<?php
namespace App\Jobs\Rapports;

use App\Domain\Approvisionnement\Models\NiveauStock;
use App\Domain\Execution\Models\{Projet, Tache};
use App\Domain\Finances\Models\Facture;
use App\Domain\QHSE\Models\IncidentSecurite;
use App\Domain\Socle\Models\User;
use App\Domain\Socle\Services\CommunicationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererRapportJournalier implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;
    public string $queue = 'rapports';

    public function handle(CommunicationService $communication): void
    {
        $stats = [
            'date'              => now()->format('d/m/Y'),
            'chantiers_actifs'  => Projet::where('statut', 'en_cours')->count(),
            'taches_retard'     => Tache::enRetard()->count(),
            'factures_echues'   => Facture::whereIn('statut', ['emise', 'partiellement_payee'])
                ->whereDate('date_echeance', '<', now())->count(),
            'incidents_mois'    => IncidentSecurite::whereMonth('date_incident', now()->month)->count(),
            'stock_alertes'     => NiveauStock::whereRaw(
                'quantite <= (SELECT seuil_alerte_stock_min FROM materiaux WHERE materiaux.id = niveau_stocks.materiau_id)'
            )->count(),
            'ca_mois'           => (float) Facture::where('type', 'client')
                ->whereMonth('date_facture', now()->month)
                ->where('statut', '!=', 'annulee')
                ->sum('montant_ttc'),
        ];

        $corps = view('emails.rapports.journalier', compact('stats'))->render();

        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'directeur_technique'])
        )->where('est_actif', true)
         ->pluck('email')
         ->filter()
         ->map(fn($email) => ['email' => $email])
         ->toArray();

        if (!empty($destinataires)) {
            $communication->envoyerDepuisTemplate(
                'rapport.journalier',
                ['date' => now()->format('d/m/Y'), 'contenu' => $corps],
                $destinataires
            );
        }

        Log::info('[Job] Rapport journalier généré', $stats);
    }
}