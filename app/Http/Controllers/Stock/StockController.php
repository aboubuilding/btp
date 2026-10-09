<?php
namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Stock\StoreMouvementStockRequest;
use App\Domain\Approvisionnement\Services\StockService;
use App\Domain\Approvisionnement\Repositories\StockRepositoryInterface;
use App\Domain\Socle\Exceptions\RegleGestionException;
use Illuminate\Http\Request;

class StockController extends Controller
{
    use HandlesModals;

    public function __construct(
        private StockService $service,
        private StockRepositoryInterface $repo,
    ) {}

    public function index(Request $request)
    {
        $stocks = $this->repo->niveauxAvecAlertes($request->only(['entrepot_id', 'search', 'categorie_id']));
        $stats = $this->repo->statistiques();
        return view('logistique.stocks.index', compact('stocks', 'stats'));
    }

    public function createMouvement()
    {
        $this->authorize('create', \App\Domain\Approvisionnement\Models\MouvementStock::class);
        return view('logistique.stocks.partials._mouvement_form');
    }

    public function storeMouvement(StoreMouvementStockRequest $request)
    {
        try {
            $data = $request->validated();
            if ($data['type'] === 'entree') {
                $this->service->entrer($data['entrepot_id'], $data['materiau_id'], $data['quantite'], $data['prix_unitaire'] ?? 0);
            } elseif ($data['type'] === 'sortie') {
                $this->service->sortir($data['entrepot_id'], $data['materiau_id'], $data['quantite'], $data['projet_id']);
            } elseif ($data['type'] === 'ajustement') {
                $this->service->entrer($data['entrepot_id'], $data['materiau_id'], $data['quantite'], $data['prix_unitaire'] ?? 0, ['type' => 'ajustement']);
            }
            return $this->modalSuccess('Mouvement enregistré.');
        } catch (RegleGestionException $e) {
            return $this->modalError($e->getMessage());
        }
    }
}