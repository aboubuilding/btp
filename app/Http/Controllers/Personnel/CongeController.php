<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Personnel\{StoreCongeRequest, ApprouverCongeRequest};
use App\Domain\Personnel\Models\DemandeConge;
use App\Domain\Personnel\Services\CongeService;
use Illuminate\Http\Request;

class CongeController extends Controller
{
    use HandlesModals;

    public function __construct(private CongeService $service) {}

    public function index(Request $request)
    {
        $conges = DemandeConge::with(['employe', 'typeConge'])
            ->when($request->statut, fn($q, $v) => $q->where('statut', $v))
            ->latest()
            ->paginate(25);
        return view('rh.conges.index', compact('conges'));
    }

    public function create()
    {
        $this->authorize('create', DemandeConge::class);
        return view('rh.conges.partials._form', ['conge' => new DemandeConge()]);
    }

    public function store(StoreCongeRequest $request)
    {
        $this->service->creer($request->validated(), auth()->id());
        return $this->modalSuccess('Demande de congé enregistrée.');
    }

    public function edit(DemandeConge $conge)
    {
        $this->authorize('update', $conge);
        return view('rh.conges.partials._form', compact('conge'));
    }

    public function update(StoreCongeRequest $request, DemandeConge $conge)
    {
        $conge->update($request->validated());
        return $this->modalSuccess('Demande mise à jour.');
    }

    public function approuver(ApprouverCongeRequest $request, DemandeConge $conge)
    {
        $this->authorize('approuver', $conge);
        $this->service->approuver($conge, auth()->id());
        return $this->modalSuccess('Congé approuvé.');
    }

    public function refuser(DemandeConge $conge)
    {
        $this->authorize('refuser', $conge);
        $this->service->refuser($conge, auth()->id());
        return $this->modalSuccess('Congé refusé.');
    }
}