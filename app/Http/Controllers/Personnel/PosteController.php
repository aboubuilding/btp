<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Personnel\StorePosteRequest;
use App\Domain\Personnel\Models\Poste;

class PosteController extends Controller
{
    use HandlesModals;

    public function create()
    {
        $this->authorize('create', Poste::class);
        return view('rh.postes.partials._form', ['poste' => new Poste()]);
    }

    public function store(StorePosteRequest $request)
    {
        $p = Poste::create($request->validated());
        return $this->modalSuccess("Poste « {$p->nom} » créé.");
    }

    public function edit(Poste $poste)
    {
        $this->authorize('update', $poste);
        return view('rh.postes.partials._form', compact('poste'));
    }

    public function update(StorePosteRequest $request, Poste $poste)
    {
        $poste->update($request->validated());
        return $this->modalSuccess('Poste mis à jour.');
    }

    public function destroy(Poste $poste)
    {
        $this->authorize('delete', $poste);
        $poste->update(['etat' => 0]);
        return $this->modalSuccess('Poste désactivé.');
    }
}