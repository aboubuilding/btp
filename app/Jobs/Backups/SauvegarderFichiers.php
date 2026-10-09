<?php
namespace App\Jobs\Backups;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Storage};
use ZipArchive;

class SauvegarderFichiers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 3600;
    public string $queue = 'backups';

    public function handle(): void
    {
        $filename = 'backups/files-' . now()->format('Ymd-His') . '.zip';
        $fullPath = storage_path('app/' . $filename);

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($fullPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Impossible de créer l\'archive ZIP.');
        }

        $dossiers = ['documents', 'depenses', 'equipements', 'pv', 'communications'];
        $count = 0;

        foreach ($dossiers as $dossier) {
            $path = storage_path('app/' . $dossier);
            if (!is_dir($path)) continue;

            $this->ajouterDossierAuZip($zip, $path, $dossier);
            $count++;
        }

        $zip->close();

        // Supprime les ZIP de plus de 30 jours
        $this->supprimerAnciens(30);

        Log::info('[Job] Sauvegarde fichiers réussie', [
            'file'     => $filename,
            'dossiers' => $count,
            'size'     => filesize($fullPath),
        ]);
    }

    private function ajouterDossierAuZip(ZipArchive $zip, string $path, string $prefix): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relative = $prefix . '/' . $iterator->getSubPathName();
                $zip->addFile($file->getRealPath(), $relative);
            }
        }
    }

    private function supprimerAnciens(int $jours): void
    {
        if (!Storage::disk('local')->exists('backups')) return;

        foreach (Storage::disk('local')->files('backups') as $fichier) {
            if (str_starts_with(basename($fichier), 'files-')
                && Storage::disk('local')->lastModified($fichier) < now()->subDays($jours)->timestamp) {
                Storage::disk('local')->delete($fichier);
            }
        }
    }
}