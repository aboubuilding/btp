<?php
namespace App\Http\Controllers\Chantier;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Domain\Execution\Models\{LigneBudget, Projet};
use Illuminate\Http\Request;

class LigneBudgetController extends Controller
{
    use HandlesModals;

    public function create(Projet $projet)
    {
        $this->authorize('update', $projet);
        return view('chantiers.budgets.partials._form', [
            'projet' => $projet,
            'ligne'  => new LigneBudget(),
        ]);
    }

    public function store(Request $request, Projet $projet)
    {
        $data = $request->validate([
            'libelle'       => ['required', 'string', 'max:100'],
            'montant_prevu' => ['required', 'numeric', 'min:0'],
        ]);

        $ligne = $projet->ligneBudgets()->create($data);
        return $this->modalSuccess("Ligne « {$ligne->libelle} » créée.");
    }

    public function update(Request $request, LigneBudget $ligne)
    {
        $this->authorize('update', $ligne);
        $data = $request->validate([
            'libelle'       => ['required', 'string', 'max:100'],
            'montant_prevu' => ['required', 'numeric', 'min:0'],
        ]);
        $ligne->update($data);
        return $this->modalSuccess('Ligne mise à jour.');
    }

    public function destroy(LigneBudget $ligne)
    {
        $this->authorize('delete', $ligne);
        $ligne->update(['etat' => 0]);
        return $this->modalSuccess('Ligne supprimée.');
    }
}