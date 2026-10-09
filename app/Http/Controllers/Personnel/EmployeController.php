<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Personnel\{StoreEmployeRequest, UpdateEmployeRequest};
use App\Domain\Personnel\Models\Employe;
use App\Domain\Personnel\Services\EmployeService;
use Illuminate\Http\Request;

class EmployeController extends Controller
{
    public function __construct(private EmployeService $service) {}

    public function index(Request $request)
    {
        $employes = $this->service->paginate($request->only(['search', 'statut', 'type_contrat', 'departement_id', 'poste_id']));
        return view('rh.employes.index', compact('employes'));
    }

    public function create()
    {
        $this->authorize('create', Employe::class);
        return view('rh.employes.create');
    }

    public function store(StoreEmployeRequest $request)
    {
        $employe = $this->service->creer($request->validated());
        return redirect()->route('rh.employes.show', $employe)->with('success', 'Employé créé.');
    }

    public function show(Employe $employe)
    {
        $this->authorize('view', $employe);
        $employe = $this->service->avecDetails($employe);
        return view('rh.employes.show', compact('employe'));
    }

    public function edit(Employe $employe)
    {
        $this->authorize('update', $employe);
        return view('rh.employes.edit', compact('employe'));
    }

    public function update(UpdateEmployeRequest $request, Employe $employe)
    {
        $this->service->mettreAJour($employe, $request->validated());
        return redirect()->route('rh.employes.show', $employe)->with('success', 'Employé mis à jour.');
    }

    public function destroy(Employe $employe)
    {
        $this->authorize('delete', $employe);
        $this->service->desactiver($employe);
        return back()->with('success', 'Employé désactivé.');
    }
}