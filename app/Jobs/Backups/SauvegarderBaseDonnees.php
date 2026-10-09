<?php
namespace App\Jobs\Backups;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Storage};
use Symfony\Component\Process\Process;

class SauvegarderBaseDonnees implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 3600; // 1h
    public string $queue = 'backups';

    public function handle(): void
    {
        $filename = 'backups/db-' . now()->format('Ymd-His') . '.sql';
        $fullPath = storage_path('app/' . $filename);

        // S'assure que le dossier existe
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $config = config('database.connections.mysql');

        $process = new Process([
            'mysqldump',
            '-h', $config['host'],
            '-P', $config['port'] ?? '3306',
            '-u', $config['username'],
            '-p' . $config['password'],
            $config['database'],
            '--single-transaction',
            '--routines',
            '--triggers',
            '--result-file=' . $fullPath,
        ]);

        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('[Job] Échec sauvegarde BDD', ['error' => $process->getErrorOutput()]);
            throw new \RuntimeException('Sauvegarde BDD échouée : ' . $process->getErrorOutput());
        }

        // Supprime les backups de plus de 30 jours
        $this->supprimerAnciens(30);

        Log::info('[Job] Sauvegarde BDD réussie', [
            'file' => $filename,
            'size' => filesize($fullPath),
        ]);
    }

    private function supprimerAnciens(int $jours): void
    {
        if (!Storage::disk('local')->exists('backups')) return;

        foreach (Storage::disk('local')->files('backups') as $fichier) {
            if (Storage::disk('local')->lastModified($fichier) < now()->subDays($jours)->timestamp) {
                Storage::disk('local')->delete($fichier);
            }
        }
    }
}