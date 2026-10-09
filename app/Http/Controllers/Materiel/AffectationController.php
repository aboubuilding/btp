<?php
namespace App\Http\Controllers\Materiel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Materiel\{StoreAffectationRequest, FermerAffectationRequest};
use App\Domain\ParcMateriel\Models\{AffectationEquipement, Equipement};
use App\Domain\ParcMateriel\Services\AffectationService;
use App\Domain\Socle\Exceptions\RegleGestionException;

class AffectationController extends Controller
{
    use HandlesModals;

    public function __construct(private AffectationService $service) {}

    public function create(Equipement $equipement)
    {
        $this->authorize('affecter', $equipement);
        return view('materiel.affectations.partials._form', compact('equipement'));
    }

    public function store(StoreAffectationRequest $request, Equipement $equipement)
    {
        try {
            $this->service->affecter($equipement, $request->projet_id, $request->validated(), auth()->id());
            return $this->modalSuccess('Engin affecté au chantier.');
        } catch (RegleGestionException $e) {
            return $this->modalError($e->getMessage());
        }
    }

    public function fermer(FermerAffectationRequest $request, AffectationEquipement $affectation)
    {
        $this->authorize('fermer', $affectation);
        $this->service->fermer($affectation, $request->input('compteur_fin'));
        return $this->modalSuccess('Affectation clôturée.');
    }
}