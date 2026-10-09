<?php
namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Commercial\{StoreAvenantRequest, SignerAvenantRequest};
use App\Domain\Commercial\Models\{AvenantMarche, Marche};
use App\Domain\Commercial\Services\MarcheService;

class AvenantMarcheController extends Controller
{
    use HandlesModals;

    public function __construct(private MarcheService $service) {}

    public function create(Marche $marche)
    {
        $this->authorize('ajouterAvenant', $marche);
        return view('commercial.avenants.partials._form', ['marche' => $marche, 'avenant' => new AvenantMarche()]);
    }

    public function store(StoreAvenantRequest $request, Marche $marche)
    {
        try {
            $avenant = $this->service->ajouterAvenant($marche, $request->validated());
            return $this->modalSuccess("Avenant « {$avenant->numero} » enregistré.");
        } catch (\App\Domain\Socle\Exceptions\RegleGestionException $e) {
            return $this->modalError($e->getMessage());
        }
    }

    public function edit(AvenantMarche $avenant)
    {
        $this->authorize('update', $avenant);
        return view('commercial.avenants.partials._form', [
            'marche'  => $avenant->marche,
            'avenant' => $avenant,
        ]);
    }

    public function update(StoreAvenantRequest $request, AvenantMarche $avenant)
    {
        $avenant->update($request->validated());
        return $this->modalSuccess('Avenant mis à jour.');
    }

    public function signer(SignerAvenantRequest $request, AvenantMarche $avenant)
    {
        $this->authorize('signer', $avenant);
        $this->service->signerAvenant($avenant);
        return $this->modalSuccess('Avenant signé.');
    }

    public function destroy(AvenantMarche $avenant)
    {
        $this->authorize('delete', $avenant);
        $avenant->update(['etat' => 0]);
        return $this->modalSuccess('Avenant supprimé.');
    }
}