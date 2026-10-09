<?php
namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Finances\{StoreCaisseRequest, AlimenterCaisseRequest};
use App\Domain\Finances\Models\Caisse;
use App\Domain\Finances\Services\CaisseService;

class CaisseController extends Controller
{
    use HandlesModals;

    public function __construct(private CaisseService $service) {}

    public function index()
    {
        $caisses = Caisse::with('projet')->where('etat', 1)->get();
        return view('finances.caisses.index', compact('caisses'));
    }

    public function create()
    {
        $this->authorize('create', Caisse::class);
        return view('finances.caisses.partials._form', ['caisse' => new Caisse()]);
    }

    public function store(StoreCaisseRequest $request)
    {
        $c = $this->service->creer($request->validated());
        return $this->modalSuccess("Caisse « {$c->libelle} » créée.");
    }

    public function edit(Caisse $caisse)
    {
        $this->authorize('update', $caisse);
        return view('finances.caisses.partials._form', compact('caisse'));
    }

    public function update(StoreCaisseRequest $request, Caisse $caisse)
    {
        $this->service->mettreAJour($caisse, $request->validated());
        return $this->modalSuccess('Caisse mise à jour.');
    }

    public function alimenter(AlimenterCaisseRequest $request, Caisse $caisse)
    {
        $this->service->alimenter($caisse, (float) $request->montant);
        return $this->modalSuccess('Caisse alimentée.');
    }
}