<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Paie\{StoreAvanceRequest, ApprouverAvanceRequest};
use App\Domain\Personnel\Models\AvanceSalaire;
use App\Domain\Personnel\Services\AvanceService;
use Illuminate\Http\Request;

class AvanceSalaireController extends Controller
{
    use HandlesModals;

    public function __construct(private AvanceService $service) {}

    public function index(Request $request)
    {
        $avances = AvanceSalaire::with('employe')
            ->when($request->statut, fn($q, $v) => $q->where('statut', $v))
            ->latest()
            ->paginate(25);
        return view('rh.avances.index', compact('avances'));
    }

    public function create()
    {
        $this->authorize('create', AvanceSalaire::class);
        return view('rh.avances.partials._form', ['avance' => new AvanceSalaire()]);
    }

    public function store(StoreAvanceRequest $request)
    {
        $this->service->creer($request->validated(), auth()->id());
        return $this->modalSuccess('Avance enregistrée.');
    }

    public function edit(AvanceSalaire $avance)
    {
        $this->authorize('update', $avance);
        return view('rh.avances.partials._form', compact('avance'));
    }

    public function update(StoreAvanceRequest $request, AvanceSalaire $avance)
    {
        $avance->update($request->validated());
        return $this->modalSuccess('Avance mise à jour.');
    }

    public function approuver(ApprouverAvanceRequest $request, AvanceSalaire $avance)
    {
        $this->service->approuver($avance, auth()->id());
        return $this->modalSuccess('Avance approuvée.');
    }
}