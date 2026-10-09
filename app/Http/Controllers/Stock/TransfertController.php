<?php
namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Stock\{StoreTransfertRequest, ApprouverTransfertRequest};
use App\Domain\Approvisionnement\Models\TransfertStock;
use App\Domain\Approvisionnement\Services\TransfertService;
use App\Domain\Socle\Exceptions\RegleGestionException;

class TransfertController extends Controller
{
    use HandlesModals;

    public function __construct(private TransfertService $service) {}

    public function index()
    {
        $transferts = TransfertStock::with(['entrepotSource', 'entrepotDestination', 'materiau'])->latest()->paginate(25);
        return view('logistique.transferts.index', compact('transferts'));
    }

    public function create()
    {
        $this->authorize('create', TransfertStock::class);
        return view('logistique.transferts.partials._form');
    }

    public function store(StoreTransfertRequest $request)
    {
        try {
            $transfert = $this->service->demander($request->validated(), auth()->id());
            return $this->modalSuccess('Demande de transfert enregistrée.');
        } catch (RegleGestionException $e) {
            return $this->modalError($e->getMessage());
        }
    }

    public function approuver(ApprouverTransfertRequest $request, TransfertStock $transfert)
    {
        try {
            $this->service->approuver($transfert, auth()->id());
            return $this->modalSuccess('Transfert approuvé.');
        } catch (RegleGestionException $e) {
            return $this->modalError($e->getMessage());
        }
    }

    public function rejeter(TransfertStock $transfert)
    {
        $this->authorize('rejeter', $transfert);
        $this->service->rejeter($transfert, auth()->id());
        return $this->modalSuccess('Transfert rejeté.');
    }
}