<?php
namespace App\Jobs\Rapports;

use App\Domain\Execution\Models\Projet;
use App\Domain\Finances\Models\Facture;
use App\Domain\Personnel\Models\Presence;
use App\Domain\Socle\Models\User;
use App\Notifications\ExportDisponibleNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Storage};

class ExporterStatistiques implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 900; // 15 min
    public string $queue = 'exports';

    public function __construct(
        public int $userId,
        public string $type = 'mensuel', // mensuel|trimestriel|annuel
    ) {}

    public function handle(): void
    {
        $data = $this->collecterDonnees();

        $filename = 'exports/stats-' . $this->type . '-' . now()->format('Ymd-His') . '.json';
        Storage::disk('local')->put(
            $filename,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        // Notifie l'utilisateur
        $user = User::find($this->userId);
        $user?->notify(new ExportDisponibleNotification($filename));

        Log::info('[Job] Export statistiques généré', [
            'user_id' => $this->userId,
            'file'    => $filename,
        ]);
    }

    private function collecterDonnees(): array
    {
        return [
            'genere_le'   => now()->toDateTimeString(),
            'type'        => $this->type,
            'chantiers'   => Projet::select('code', 'nom', 'statut', 'pourcentage_avancement', 'budget_prevu', 'budget_reel')->get(),
            'factures'    => Facture::select('numero_facture', 'type', 'montant_ttc', 'statut', 'date_facture')->get(),
            'presences'   => Presence::with('employe:id,nom,prenom')
                ->select('employee_id', 'date', 'heures_travaillees', 'statut')
                ->limit(5000)->get(),
        ];
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[Job] Échec export statistiques', [
            'user_id' => $this->userId,
            'error'   => $exception->getMessage(),
        ]);
    }
}