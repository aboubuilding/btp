<?php
namespace App\Http\Controllers\Qhse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Qhse\{StoreNonConformiteRequest, LeverNonConformiteRequest};
use App\Domain\QHSE\Models\NonConformite;
use App\Domain\QHSE\Services\NonConformiteService;
use Illuminate\Http\Request;

class NonConformiteController extends Controller
{
    public function __construct(private NonConformiteService $service) {}

    public function index(Request $request)
    {
        $nonConformites = NonConformite::with(['projet', 'responsable'])
            ->when($request->statut, fn($q, $v) => $q->where('statut', $v))
            ->latest('date_constat')
            ->paginate(25);
        return view('qhse.non-conformites.index', compact('nonConformites'));
    }

    public function create()
    {
        $this->authorize('create', NonConformite::class);
        return view('qhse.non-conformites.create');
    }

    public function store(StoreNonConformiteRequest $request)
    {
        $this->service->creer($request->validated());
        return redirect()->route('qhse.non-conformites.index')->with('success', 'Non-conformité créée.');
    }

    public function update(StoreNonConformiteRequest $request, NonConformite $nonConformite)
    {
        $this->authorize('update', $nonConformite);
        $this->service->mettreAJour($nonConformite, $request->validated());
        return back()->with('success', 'Non-conformité mise à jour.');
    }

    public function lever(LeverNonConformiteRequest $request, NonConformite $nonConformite)
    {
        $this->authorize('lever', $nonConformite);
        $this->service->lever($nonConformite);
        return back()->with('success', 'Non-conformité levée.');
    }
}