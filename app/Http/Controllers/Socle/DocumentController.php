<?php
namespace App\Http\Controllers\Socle;

use App\Http\Controllers\Controller;
use App\Domain\Socle\Models\Document;
use App\Domain\Socle\Services\DocumentService;

class DocumentController extends Controller
{
    public function __construct(private DocumentService $service) {}

    public function download(Document $document)
    {
        return $this->service->telecharger($document);
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);
        $this->service->supprimer($document);
        return back()->with('success', 'Document supprimé.');
    }
}