<?php
namespace App\Jobs\Alertes;

use App\Domain\Execution\Models\Tache;
use App\Domain\Socle\Models\User;
use App\Notifications\TacheEnRetardNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Notification};

class VerifierTachesEnRetard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'alertes';

    public function handle(): void
    {
        $taches = Tache::enRetard()->with(['projet', 'assigne'])->get();

        // Regroupement par projet pour éviter le spam
        $parProjet = $taches->groupBy('projet_id');

        foreach ($parProjet as $projetId => $liste) {
            $projet = $liste->first()->projet;
            if (!$projet) continue;

            // Notifie conducteur + DT du projet
            $destinataires = User::whereHas('employe', fn($q) => $q
                ->whereIn('id', array_filter([
                    $projet->conducteur_travaux_id,
                    $projet->chef_chantier_id,
                ]))
            )->where('est_actif', true)->get();

            // Ajoute DT + DG
            $direction = User::whereHas('role', fn($q) =>
                $q->whereIn('slug', ['directeur_technique', 'direction'])
            )->where('est_actif', true)->get();

            $destinataires = $destinataires->merge($direction)->unique('id');

            if ($destinataires->isNotEmpty()) {
                Notification::send($destinataires, new TacheEnRetardNotification($projet, $liste));
            }
        }

        Log::info('[Job] Vérification tâches en retard', ['total' => $taches->count()]);
    }
}