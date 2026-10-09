<?php
namespace App\Jobs\Alertes;

use App\Domain\ParcMateriel\Models\DocumentEquipement;
use App\Domain\Personnel\Models\DocumentEmploye;
use App\Domain\Socle\Models\User;
use App\Domain\Socle\Services\JournalService;
use App\Notifications\DocumentBientotExpireNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{DB, Log, Notification};

class VerifierDocumentsExpires implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;
    public int $backoff = 60;
    public string $queue = 'alertes';

    public function __construct(
        public int $joursAvantExpiration = 30,
    ) {}

    public function handle(JournalService $journal): void
    {
        $dateCible = now()->addDays($this->joursAvantExpiration);
        $nbEquipements = 0;
        $nbEmployes = 0;

        // === 1. Documents équipements (assurance, visite technique, etc.) ===
        DocumentEquipement::with('equipement')
            ->whereNotNull('date_expiration')
            ->whereDate('date_expiration', '>=', now())
            ->whereDate('date_expiration', '<=', $dateCible)
            ->chunkById(100, function ($documents) use (&$nbEquipements) {
                foreach ($documents as $document) {
                    $this->notifierResponsablesMateriel($document);
                    $nbEquipements++;
                }
            });

        // === 2. Documents employés (habilitations, certificats médicaux) ===
        DocumentEmploye::with('employe')
            ->whereNotNull('date_expiration')
            ->whereDate('date_expiration', '>=', now())
            ->whereDate('date_expiration', '<=', $dateCible)
            ->chunkById(100, function ($documents) use (&$nbEmployes) {
                foreach ($documents as $document) {
                    $this->notifierRHQhse($document);
                    $nbEmployes++;
                }
            });

        $journal->log('job.documents_verifies', null, null, null, [
            'equipements' => $nbEquipements,
            'employes'    => $nbEmployes,
        ]);

        Log::info('[Job] Vérification documents expirés terminée', [
            'equipements' => $nbEquipements,
            'employes'    => $nbEmployes,
        ]);
    }

    private function notifierResponsablesMateriel(DocumentEquipement $document): void
    {
        $destinataires = User::whereHas('role', fn($q) => $q->whereIn('slug', [
            'responsable_materiel', 'responsable_qhse', 'directeur_technique',
        ]))->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new DocumentBientotExpireNotification($document));
        }
    }

    private function notifierRHQhse(DocumentEmploye $document): void
    {
        $destinataires = User::whereHas('role', fn($q) => $q->whereIn('slug', [
            'rh', 'responsable_qhse',
        ]))->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new DocumentBientotExpireNotification($document));
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[Job] Échec VerifierDocumentsExpires', [
            'error' => $exception->getMessage(),
        ]);
    }
}