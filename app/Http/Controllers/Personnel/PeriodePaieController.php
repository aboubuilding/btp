<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Paie\{StorePeriodePaieRequest, GenererBulletinsRequest, CloturerPeriodePaieRequest};
use App\Domain\Personnel\Models\PeriodePaie;
use App\Domain\Personnel\Services\PaieService;
use App\Domain\Socle\Exceptions\RegleGestionException;
use Illuminate\Http\Request;

class PeriodePaieController extends Controller
{
    use HandlesModals;

    public function __construct(private PaieService $service) {}

    public function index()
    {
        $periodes = $this->service->paginatePeriodes();
        return view('rh.periodes-paie.index', compact('periodes'));
    }

    public function create()
    {
        $this->authorize('create', PeriodePaie::class);
        return view('rh.periodes-paie.partials._form', ['periode' => new PeriodePaie()]);
    }

    public function store(StorePeriodePaieRequest $request)
    {
        $p = $this->service->creerPeriode($request->validated());
        return $this->modalSuccess("Période « {$p->libelle} » créée.");
    }

    public function show(PeriodePaie $periode)
    {
        $this->authorize('view', $periode);
        $periode = $this->service->avecBulletins($periode);
        return view('rh.periodes-paie.show', compact('periode'));
    }

    public function edit(PeriodePaie $periode)
    {
        if ($periode->statut !== 'ouverte') return $this->modalError('Seules les périodes ouvertes sont modifiables.');
        return view('rh.periodes-paie.partials._form', compact('periode'));
    }

    public function update(StorePeriodePaieRequest $request, PeriodePaie $periode)
    {
        if ($periode->statut !== 'ouverte') return $this->modalError('Période déjà clôturée.');
        $periode->update($request->validated());
        return $this->modalSuccess('Période mise à jour.');
    }

    public function generer(GenererBulletinsRequest $request, PeriodePaie $periode)
    {
        try {
            $n = $this->service->genererBulletins($periode);
            return back()->with('success', "{$n} bulletins générés.");
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cloturer(CloturerPeriodePaieRequest $request, PeriodePaie $periode)
    {
        try {
            $this->service->cloturer($periode, auth()->id());
            return back()->with('success', 'Période clôturée.');
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}