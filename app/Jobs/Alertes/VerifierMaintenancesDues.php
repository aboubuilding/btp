<?php
namespace App\Jobs\Alertes;

use App\Domain\ParcMateriel\Models\MaintenanceEquipement;
use App\Domain\Socle\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{DB, Log, Notification};

class VerifierMaintenancesDues implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'alertes';

    public function __construct(public int $joursAvant = 15) {}

    public function handle(): void
    {
        $maintenances = MaintenanceEquipement::with('equipement')
            ->whereNotNull('prochaine_echeance')
            ->whereDate('prochaine_echeance', '>=', now())
            ->whereDate('prochaine_echeance', '<=', now()->addDays($this->joursAvant))
            ->get();

        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['responsable_materiel', 'directeur_technique'])
        )->where('est_actif', true)->get();

        foreach ($maintenances as $maintenance) {
            // Crée une notification personnalisée
            $notification = new \App\Notifications\MaintenanceDueNotification($maintenance);

            if ($destinataires->isNotEmpty()) {
                Notification::send($destinataires, $notification);
            }
        }

        Log::info('[Job] Vérification maintenances', ['total' => $maintenances->count()]);
    }
}