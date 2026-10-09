<?php
namespace App\Http\Controllers\Chantier;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Execution\StoreEquipeRequest;
use App\Domain\Execution\Models\Projet;
use App\Domain\Personnel\Models\Employe;

class EquipeController extends Controller
{
    use HandlesModals;

    public function create(Projet $projet)
    {
        $this->authorize('update', $projet);
        return view('chantiers.equipe.partials._form', compact('projet'));
    }

    public function store(StoreEquipeRequest $request, Projet $projet)
    {
        $projet->equipe()->attach($request->employee_id, [
            'role_chantier' => $request->role_chantier,
            'date_debut'    => $request->date_debut,
            'date_fin'      => $request->date_fin,
        ]);

        $employe = Employe::find($request->employee_id);
        return $this->modalSuccess("{$employe->nom_complet} affecté au chantier.");
    }

    public function destroy(Projet $projet, Employe $employe)
    {
        $this->authorize('update', $projet);
        $projet->equipe()->detach($employe->id);
        return $this->modalSuccess('Employé retiré du chantier.');
    }
}