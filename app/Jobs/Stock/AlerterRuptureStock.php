<?php
namespace App\Jobs\Stock;

use App\Domain\Approvisionnement\Models\{Materiau, NiveauStock};
use App\Domain\Socle\Models\User;
use App\Notifications\StockSousSeuilNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Notification};

class AlerterRuptureStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'alertes';

    public function handle(): void
    {
        $niveaux = NiveauStock::with(['materiau', 'entrepot'])
            ->whereHas('materiau', fn($q) => $q->where('etat', 1))
            ->whereRaw('quantite <= (SELECT seuil_alerte_stock_min * 0.5 FROM materiaux WHERE materiaux.id = niveau_stocks.materiau_id)')
            ->get();

        if ($niveaux->isEmpty()) return;

        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['responsable_achat', 'direction', 'directeur_technique'])
        )->where('est_actif', true)->get();

        foreach ($niveaux as $niveau) {
            if ($destinataires->isNotEmpty()) {
                Notification::send(
                    $destinataires,
                    new StockSousSeuilNotification($niveau->materiau, $niveau)
                );
            }
        }

        Log::info('[Job] Alerte rupture stock', ['count' => $niveaux->count()]);
    }
}