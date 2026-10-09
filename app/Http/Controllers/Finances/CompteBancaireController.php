<?php
namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Finances\StoreCompteBancaireRequest;
use App\Domain\Finances\Models\CompteBancaire;

class CompteBancaireController extends Controller
{
    use HandlesModals;

    public function index()
    {
        $comptes = CompteBancaire::where('etat', 1)->get();
        return view('finances.comptes-bancaires.index', compact('comptes'));
    }

    public function create()
    {
        $this->authorize('create', CompteBancaire::class);
        return view('finances.comptes-bancaires.partials._form', ['compte' => new CompteBancaire()]);
    }

    public function store(StoreCompteBancaireRequest $request)
    {
        $data = $request->validated();
        $data['solde_actuel'] = $data['solde_initial'] ?? 0;
        CompteBancaire::create($data);
        return $this->modalSuccess('Compte créé.');
    }

    public function edit(CompteBancaire $compteBancaire)
    {
        $this->authorize('update', $compteBancaire);
        return view('finances.comptes-bancaires.partials._form', ['compte' => $compteBancaire]);
    }

    public function update(StoreCompteBancaireRequest $request, CompteBancaire $compteBancaire)
    {
        $compteBancaire->update($request->validated());
        return $this->modalSuccess('Compte mis à jour.');
    }
}