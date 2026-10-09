<?php
namespace App\Http\Controllers\Socle;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Socle\StoreParametreRequest;
use App\Domain\Socle\Models\Parametre;
use App\Domain\Socle\Repositories\ParametreRepositoryInterface;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    use HandlesModals;

    public function __construct(private ParametreRepositoryInterface $repo) {}

    public function index()
    {
        $parametres = Parametre::where('etat', 1)->orderBy('cle')->get()->groupBy(fn($p) => explode('.', $p->cle)[0]);
        return view('admin.parametres.index', compact('parametres'));
    }

    public function create()
    {
        $this->authorize('create', Parametre::class);
        return view('admin.parametres.partials._form', ['parametre' => new Parametre()]);
    }

    public function store(StoreParametreRequest $request)
    {
        $p = $this->repo->create($request->validated());
        return $this->modalSuccess("Paramètre « {$p->cle} » créé.");
    }

    public function edit(Parametre $parametre)
    {
        $this->authorize('update', $parametre);
        return view('admin.parametres.partials._form', compact('parametre'));
    }

    public function update(StoreParametreRequest $request, Parametre $parametre)
    {
        $this->repo->update($parametre, $request->validated());
        $this->repo->setValeur($parametre->cle, $parametre->valeur);
        return $this->modalSuccess('Paramètre mis à jour.');
    }

    public function destroy(Parametre $parametre)
    {
        $this->authorize('delete', $parametre);
        $parametre->update(['etat' => 0]);
        return $this->modalSuccess('Paramètre supprimé.');
    }
}