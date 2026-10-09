<?php
namespace App\Http\Controllers\Qhse;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Qhse\{StoreReserveRequest, LeverReserveRequest};
use App\Domain\QHSE\Models\{PvReception, Reserve};
use App\Domain\QHSE\Services\ReserveService;

class ReserveController extends Controller
{
    use HandlesModals;

    public function __construct(private ReserveService $service) {}

    public function create(PvReception $pv)
    {
        $this->authorize('update', $pv);
        return view('qhse.reserves.partials._form', [
            'pv'      => $pv,
            'reserve' => new Reserve(),
        ]);
    }

    public function store(StoreReserveRequest $request, PvReception $pv)
    {
        $this->service->creer($pv, $request->validated());
        return $this->modalSuccess('Réserve ajoutée.');
    }

    public function edit(Reserve $reserve)
    {
        $this->authorize('update', $reserve);
        return view('qhse.reserves.partials._form', [
            'pv'      => $reserve->pv,
            'reserve' => $reserve,
        ]);
    }

    public function update(StoreReserveRequest $request, Reserve $reserve)
    {
        $this->service->mettreAJour($reserve, $request->validated());
        return $this->modalSuccess('Réserve mise à jour.');
    }

    public function lever(LeverReserveRequest $request, Reserve $reserve)
    {
        $this->authorize('lever', $reserve);
        $this->service->lever($reserve, auth()->id());
        return $this->modalSuccess('Réserve levée.');
    }
}