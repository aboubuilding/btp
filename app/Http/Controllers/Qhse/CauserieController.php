<?php
namespace App\Http\Controllers\Qhse;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Qhse\StoreCauserieRequest;
use App\Domain\QHSE\Models\CauserieSecurite;
use App\Domain\QHSE\Services\CauserieService;

class CauserieController extends Controller
{
    use HandlesModals;

    public function __construct(private CauserieService $service) {}

    public function index()
    {
        $causeries = CauserieSecurite::with(['projet', 'animateur'])->latest('date')->paginate(25);
        return view('qhse.causeries.index', compact('causeries'));
    }

    public function create()
    {
        $this->authorize('create', CauserieSecurite::class);
        return view('qhse.causeries.partials._form', ['causerie' => new CauserieSecurite()]);
    }

    public function store(StoreCauserieRequest $request)
    {
        $this->service->creer($request->validated(), auth()->id());
        return $this->modalSuccess('Causerie enregistrée.');
    }

    public function edit(CauserieSecurite $causerie)
    {
        $this->authorize('update', $causerie);
        return view('qhse.causeries.partials._form', compact('causerie'));
    }

    public function update(StoreCauserieRequest $request, CauserieSecurite $causerie)
    {
        $this->service->mettreAJour($causerie, $request->validated());
        return $this->modalSuccess('Causerie mise à jour.');
    }

    public function destroy(CauserieSecurite $causerie)
    {
        $this->authorize('delete', $causerie);
        $this->service->supprimer($causerie);
        return $this->modalSuccess('Causerie supprimée.');
    }
}