<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Personnel\{StoreContratRequest, ResilierContratRequest};
use App\Domain\Personnel\Models\Contrat;
use App\Domain\Personnel\Services\ContratService;
use Illuminate\Http\Request;

class ContratController extends Controller
{
    public function __construct(private ContratService $service) {}

    public function index(Request $request)
    {
        $contrats = $this->service->paginate($request->only(['statut', 'type', 'employee_id']));
        return view('rh.contrats.index', compact('contrats'));
    }

    public function create()
    {
        $this->authorize('create', Contrat::class);
        return view('rh.contrats.create');
    }

    public function store(StoreContratRequest $request)
    {
        $contrat = $this->service->creer($request->validated());
        return redirect()->route('rh.contrats.index')->with('success', 'Contrat créé.');
    }

    public function edit(Contrat $contrat)
    {
        $this->authorize('update', $contrat);
        return view('rh.contrats.edit', compact('contrat'));
    }

    public function update(StoreContratRequest $request, Contrat $contrat)
    {
        $this->service->mettreAJour($contrat, $request->validated());
        return redirect()->route('rh.contrats.index')->with('success', 'Contrat mis à jour.');
    }

    public function resilier(ResilierContratRequest $request, Contrat $contrat)
    {
        $this->authorize('resilier', $contrat);
        $this->service->resilier($contrat);
        return back()->with('success', 'Contrat résilié.');
    }
}