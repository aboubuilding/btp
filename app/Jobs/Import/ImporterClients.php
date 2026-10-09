<?php
namespace App\Jobs\Import;

use App\Domain\Commercial\Models\Client;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{DB, Log, Storage};

class ImporterClients implements ShouldQueue
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
        if (!Storage::disk('local')->exists($this->cheminFichier)) {
            Log::error('[Job] Fichier introuvable', ['path' => $this->cheminFichier]);
            return;
        }

        $contenu = Storage::disk('local')->get($this->cheminFichier);
        $lignes = json_decode($contenu, true) ?? [];

        $importes = 0;
        $erreurs = 0;

        DB::transaction(function () use ($lignes, &$importes, &$erreurs) {
            foreach ($lignes as $ligne) {
                try {
                    Client::updateOrCreate(
                        ['nom' => $ligne['nom']],
                        [
                            'type'      => $ligne['type'] ?? 'entreprise',
                            'contact'   => $ligne['contact'] ?? null,
                            'telephone' => $ligne['telephone'] ?? null,
                            'email'     => $ligne['email'] ?? null,
                            'adresse'   => $ligne['adresse'] ?? null,
                            'nif'       => $ligne['nif'] ?? null,
                            'etat'      => 1,
                        ]
                    );
                    $importes++;
                } catch (\Throwable $e) {
                    $erreurs++;
                    Log::warning('[Job] Import client échoué', [
                        'ligne' => $ligne,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        $journal->log('job.clients_importes', null, $this->userId, null, [
            'importes' => $importes,
            'erreurs'  => $erreurs,
        ]);

        Log::info('[Job] Import clients terminé', [
            'importes' => $importes,
            'erreurs'  => $erreurs,
        ]);
    }
}