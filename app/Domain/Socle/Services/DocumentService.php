<?php
namespace App\Domain\Socle\Services;

use App\Domain\Socle\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function attacher(
        mixed $objet,
        UploadedFile $file,
        ?int $uploadedBy = null,
        ?string $disk = 'local'
    ): Document {
        $path = $file->store('documents', $disk);

        return $objet->documents()->create([
            'nom'         => $file->getClientOriginalName(),
            'chemin'      => $path,
            'mime'        => $file->getMimeType(),
            'taille'      => $file->getSize(),
            'uploaded_by' => $uploadedBy ?? auth()->id(),
        ]);
    }

    public function supprimer(Document $document): bool
    {
        if (Storage::disk('local')->exists($document->chemin)) {
            Storage::disk('local')->delete($document->chemin);
        }
        return $document->delete();
    }

    public function telecharger(Document $document)
    {
        if (!Storage::disk('local')->exists($document->chemin)) {
            abort(404, 'Fichier introuvable.');
        }
        return Storage::disk('local')->download($document->chemin, $document->nom);
    }
}