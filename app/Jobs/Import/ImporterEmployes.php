<?php
namespace App\Jobs\Import;

use App\Domain\Personnel\Models\Employe;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{DB, Log, Storage};

class ImporterEmployes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $tries = 1;
    public int $timeout = 1800;
    public string $queue = 'imports';

    public function __construct(
        public string $cheminFichier,
        public int $userId,
    ) {}

    public function handle(JournalService $journal): void
    {
        if (!Storage::disk('local')->exists($this->cheminFichier)) return;

        $lignes = json_decode(Storage::disk('local')->get($this->cheminFichier), true) ?? [];

        $importes = 0;
        $erreurs = 0;

        DB::transaction(function () use ($lignes, &$importes, &$erreurs) {
            foreach ($lignes as $ligne) {
                try {
                    Employe::updateOrCreate(
                        ['matricule' => $ligne['matricule']],
                        [
                            'nom'            => $ligne['nom'],
                            'prenom'         => $ligne['prenom'],
                            'date_naissance' => $ligne['date_naissance'] ?? null,
                            'numero_cnss'    => $ligne['numero_cnss'] ?? null,
                            'date_embauche'  => $ligne['date_embauche'] ?? null,
                            'type_contrat'   => $ligne['type_contrat'] ?? 'journalier',
                            'salaire_base'   => $ligne['salaire_base'] ?? 0,
                            'telephone'      => $ligne['telephone'] ?? null,
                            'statut'         => 'actif',
                            'etat'           => 1,
                        ]
                    );
                    $importes++;
                } catch (\Throwable $e) {
                    $erreurs++;
                    Log::warning('[Job] Import employé échoué', [
                        'ligne' => $ligne,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        $journal->log('job.employes_importes', null, $this->userId, null, [
            'importes' => $importes,
            'erreurs'  => $erreurs,
        ]);

        Log::info('[Job] Import employés terminé', compact('importes', 'erreurs'));
    }
}