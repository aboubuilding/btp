<?php
namespace App\Http\Controllers\Materiel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Materiel\StoreDocumentEquipementRequest;
use App\Domain\ParcMateriel\Models\{DocumentEquipement, Equipement};
use App\Domain\ParcMateriel\Services\DocumentEquipementService;

class DocumentEquipementController extends Controller
{
    use HandlesModals;

    public function __construct(private DocumentEquipementService $service) {}

    public function create(Equipement $equipement)
    {
        $this->authorize('create', DocumentEquipement::class);
        return view('materiel.documents.partials._form', [
            'equipement' => $equipement,
            'document'   => new DocumentEquipement(),
        ]);
    }

    public function store(StoreDocumentEquipementRequest $request, Equipement $equipement)
    {
        $data = $request->validated();
        $fichier = $request->file('chemin_fichier');
        $this->service->ajouter($equipement, $data, $fichier);
        return $this->modalSuccess('Document ajouté.');
    }
}