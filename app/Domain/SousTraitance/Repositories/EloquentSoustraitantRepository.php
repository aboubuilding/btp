<?php
namespace App\Domain\SousTraitance\Repositories;

use App\Domain\SousTraitance\Models\Soustraitant;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentSoustraitantRepository extends BaseRepository implements SoustraitantRepositoryInterface
{
    protected array $colonnesSearch = ['entreprise', 'contact', 'email', 'telephone', 'specialite'];
    protected array $filtresSimples = ['statut', 'specialite'];

    public function __construct(Soustraitant $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->withCount(['contrats', 'evaluations'])
            ->when(!empty($filtres['search']), function ($q) use ($filtres) {
                $term = $filtres['search'];
                $q->where(fn($qq) => $qq
                    ->where('entreprise', 'like', "%{$term}%")
                    ->orWhere('contact', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                );
            })
            ->when(!empty($filtres['statut']), fn($q) => $q->where('statut', $filtres['statut']))
            ->when(!empty($filtres['specialite']), fn($q) => $q->where('specialite', $filtres['specialite']))
            ->where('etat', 1)
            ->latest()
            ->paginate($parPage)
            ->withQueryString();
    }

    public function avecDetails(Soustraitant $soustraitant): Soustraitant
    {
        return $soustraitant->load([
            'contrats.projet', 'evaluations.projet',
        ]);
    }

    public function actifs(): Collection
    {
        return $this->newQuery()->where('statut', 'actif')->where('etat', 1)->get();
    }

    public function blacklistes(): Collection
    {
        return $this->newQuery()->where('statut', 'blackliste')->get();
    }

    public function parSpecialite(string $specialite): Collection
    {
        return $this->newQuery()->where('specialite', $specialite)->where('etat', 1)->get();
    }

    public function statistiques(): array
    {
        return [
            'total'      => $this->model->where('etat', 1)->count(),
            'actifs'     => $this->model->where('statut', 'actif')->count(),
            'suspendus'  => $this->model->where('statut', 'suspendu')->count(),
            'blacklistes'=> $this->model->where('statut', 'blackliste')->count(),
            'par_specialite' => $this->model->where('etat', 1)
                ->selectRaw('specialite, COUNT(*) as total')
                ->groupBy('specialite')
                ->pluck('total', 'specialite')
                ->toArray(),
        ];
    }

    public function topSoustraitants(int $limite = 10): Collection
    {
        return $this->model->newQuery()
            ->where('etat', 1)
            ->withCount('contrats')
            ->withSum('contrats as montant_total', 'montant')
            ->orderByDesc('montant_total')
            ->limit($limite)
            ->get();
    }
}