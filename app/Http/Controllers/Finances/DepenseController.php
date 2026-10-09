<?php
namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Finances\{StoreDepenseRequest, ApprouverDepenseRequest, RejeterDepenseRequest};
use App\Domain\Finances\Models\Depense;
use App\Domain\Finances\Services\DepenseService;
use App\Domain\Socle\Exceptions\RegleGestionException;
use Illuminate\Http\Request;

class DepenseController extends Controller
{
    use HandlesModals;

    public function __construct(private DepenseService $service) {}

    public function index(Request $request)
    {
        $depenses = $this->service->paginate($request->only(['projet_id', 'statut', 'categorie']));
        return view('finances.depenses.index', compact('depenses'));
    }

    public function create()
    {
        $this->authorize('create', Depense::class);
        return view('finances.depenses.create');
    }

    public function store(StoreDepenseRequest $request)
    {
        $this->service->creer($request->validated(), $request->file('document_justificatif'), auth()->id());
        return redirect()->route('finances.depenses.index')->with('success', 'Dépense enregistrée.');
    }

    public function approuver(ApprouverDepenseRequest $request, Depense $depense)
    {
        try {
            $this->service->approuver($depense, auth()->id());
            return back()->with('success', 'Dépense approuvée.');
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function rejeter(RejeterDepenseRequest $request, Depense $depense)
    {
        $this->service->rejeter($depense, auth()->id());
        return back()->with('success', 'Dépense rejetée.');
    }
}