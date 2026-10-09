<?php
namespace App\Jobs\Rapports;

use App\Domain\Execution\Models\{Projet, Situation};
use App\Domain\Finances\Models\{Facture, Paiement, Depense};
use App\Domain\Personnel\Models\Presence;
use App\Domain\QHSE\Models\IncidentSecurite;
use App\Domain\Socle\Models\User;
use App\Domain\Socle\Services\CommunicationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererRapportMensuel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;
    public string $queue = 'rapports';

    public function handle(CommunicationService $communication): void
    {
        $debut = now()->subMonth()->startOfMonth();
        $fin   = now()->subMonth()->endOfMonth();

        $stats = [
            'periode'             => $debut->translatedFormat('F Y'),
            'ca_facture'          => (float) Facture::whereBetween('date_facture', [$debut, $fin])
                ->where('type', 'client')->where('statut', '!=', 'annulee')->sum('montant_ttc'),
            'ca_encaisse'         => (float) Paiement::whereBetween('date_paiement', [$debut, $fin])
                ->where('sens', 'encaissement')->sum('montant'),
            'depenses'            => (float) Depense::whereBetween('date_depense', [$debut, $fin])
                ->where('statut', 'approuve')->sum('montant'),
            'situations'          => Situation::whereBetween('periode_debut', [$debut, $fin])->count(),
            'heures_travaillees'  => (float) Presence::whereBetween('date', [$debut, $fin])
                ->whereNotNull('valide_le')->sum('heures_travaillees'),
            'incidents'           => IncidentSecurite::whereBetween('date_incident', [$debut, $fin])->count(),
            'marge_brute'         => 0,
            'chantiers_actifs'    => Projet::where('statut', 'en_cours')->count(),
        ];

        $stats['marge_brute'] = $stats['ca_facture'] - $stats['depenses'];

        $corps = view('emails.rapports.mensuel', compact('stats', 'debut', 'fin'))->render();

        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['direction', 'directeur_technique', 'comptable'])
        )->where('est_actif', true)
         ->pluck('email')
         ->filter()
         ->map(fn($email) => ['email' => $email])
         ->toArray();

        if (!empty($destinataires)) {
            $communication->envoyerDepuisTemplate(
                'rapport.mensuel',
                ['periode' => $stats['periode'], 'contenu' => $corps],
                $destinataires
            );
        }

        Log::info('[Job] Rapport mensuel généré', $stats);
    }
}