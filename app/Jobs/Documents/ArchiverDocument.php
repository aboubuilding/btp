<?php
namespace App\Jobs\Documents;

use App\Domain\Socle\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Storage};

class ArchiverDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'documents';

    public function __construct(public int $documentId) {}

    public function handle(): void
    {
        $document = Document::find($this->documentId);
        if (!$document) return;

        if (!Storage::disk('local')->exists($document->chemin)) return;

        $cheminArchive = 'archives/documents/' . now()->format('Y/m') . '/' . basename($document->chemin);

        Storage::disk('local')->move($document->chemin, $cheminArchive);

        $document->update(['chemin' => $cheminArchive]);

        Log::info('[Job] Document archivé', [
            'document_id' => $this->documentId,
            'nouveau'     => $cheminArchive,
        ]);
    }
}