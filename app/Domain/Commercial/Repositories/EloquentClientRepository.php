<?php
namespace App\Domain\Commercial\Repositories;

use App\Domain\Commercial\Models\Client;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    protected array $colonnesSearch = ['nom', 'email', 'telephone', 'nif'];
    protected array $filtresSimples = ['type'];

    public function __construct(Client $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecCompteurs(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->withCount(['projets', 'devis', 'marches'])
            ->when(!empty($filtres['search']), function ($q) use ($filtres) {
                $term = $filtres['search'];
                $q->where(fn($qq) => $qq
                    ->where('nom', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('nif', 'like', "%{$term}%")
                );
            })
            ->when(!empty($filtres['type']), fn($q) => $q->where('type', $filtres['type']))
            ->where('etat', 1)
            ->latest()
            ->paginate($parPage)
            ->withQueryString();
    }

    public function findByNom(string $nom): ?Client
    {
        return $this->newQuery()->where('nom', $nom)->first();
    }

    public function avecHistorique(Client $client): Client
    {
        return $client->load([
            'devis', 'marches', 'projets', 'factures',
        ]);
    }

    public function statistiques(): array
    {
        return [
            'total'         => $this->model->where('etat', 1)->count(),
            'particuliers'  => $this->model->where('type', 'particulier')->where('etat', 1)->count(),
            'entreprises'   => $this->model->where('type', 'entreprise')->where('etat', 1)->count(),
            'publics'       => $this->model->where('type', 'public')->where('etat', 1)->count(),
        ];
    }

    public function clientsActifs(): Collection
    {
        return $this->newQuery()
            ->whereHas('projets', fn($q) => $q->where('statut', 'en_cours'))
            ->where('etat', 1)
            ->get();
    }

    public function topClients(int $limite = 10): Collection
    {
        return $this->model->newQuery()
            ->where('etat', 1)
            ->withSum('factures as total_facture', 'montant_ttc')
            ->orderByDesc('total_facture')
            ->limit($limite)
            ->get();
    }
}