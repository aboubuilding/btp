<?php
namespace App\Http\Controllers\SousTraitance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\SousTraitance\StorePaiementSousTraitantRequest;
use App\Domain\SousTraitance\Models\{ContratSousTraitant, PaiementSousTraitant};
use App\Domain\SousTraitance\Services\PaiementSousTraitantService;
use App\Domain\Socle\Exceptions\RegleGestionException;

class PaiementSousTraitantController extends Controller
{
    use HandlesModals;

    public function __construct(private PaiementSousTraitantService $service) {}

    public function create(ContratSousTraitant $contrat)
    {
        $this->authorize('update', $contrat);
        return view('soustraitance.paiements.partials._form', [
            'contrat'  => $contrat,
            'paiement' => new PaiementSousTraitant(),
        ]);
    }

    public function store(StorePaiementSousTraitantRequest $request, ContratSousTraitant $contrat)
    {
        try {
            $this->service->enregistrer($contrat, $request->validated());
            return $this->modalSuccess('Paiement enregistré.');
        } catch (RegleGestionException $e) {
            return $this->modalError($e->getMessage());
        }
    }
}