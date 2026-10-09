<?php
namespace App\Jobs\Import;

use App\Domain\Approvisionnement\Models\Materiau;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{DB, Log, Storage};

class ImporterMateriaux implements ShouldQueue
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
                    Materiau::updateOrCreate(
                        ['code' => $ligne['code']],
                        [
                            'nom'                    => $ligne['nom'],
                            'categorie_id'           => $ligne['categorie_id'] ?? null,
                            'unite'                  => $ligne['unite'],
                            'prix_unitaire'          => $ligne['prix_unitaire'] ?? 0,
                            'seuil_alerte_stock_min' => $ligne['seuil_alerte_stock_min'] ?? 0,
                            'etat'                   => 1,
                        ]
                    );
                    $importes++;
                } catch (\Throwable $e) {
                    $erreurs++;
                    Log::warning('[Job] Import matériau échoué', [
                        'ligne' => $ligne,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        $journal->log('job.materiaux_importes', null, $this->userId, null, [
            'importes' => $importes,
            'erreurs'  => $erreurs,
        ]);

        Log::info('[Job] Import matériaux terminé', compact('importes', 'erreurs'));
    }
}