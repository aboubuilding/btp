<?php
namespace App\Domain\ParcMateriel\Services;

use App\Domain\ParcMateriel\Models\{Equipement, DocumentEquipement};
use App\Domain\Socle\Services\JournalService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class DocumentEquipementService
{
    public function __construct(private JournalService $journal) {}

    public function ajouter(Equipement $equipement, array $data, ?UploadedFile $fichier = null): DocumentEquipement
    {
        if ($fichier) {
            $data['chemin_fichier'] = $fichier->store('equipements/documents', 'local');
        }

        $document = $equipement->documents()->create($data);
        $this->journal->log('document_equipement.cree', $document);

        return $document;
    }

    public function mettreAJour(DocumentEquipement $document, array $data, ?UploadedFile $fichier = null): DocumentEquipement
    {
        if ($fichier) {
            $data['chemin_fichier'] = $fichier->store('equipements/documents', 'local');
        }
        $document->update($data);
        return $document->fresh();
    }

    public function documentsExpirantBientot(int $jours = 30): Collection
    {
        return DocumentEquipement::expirentBientot($jours)->with('equipement')->get();
    }

    public function documentsExpires(): Collection
    {
        return DocumentEquipement::expires()->with('equipement')->get();
    }
}