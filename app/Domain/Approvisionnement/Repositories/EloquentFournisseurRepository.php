<?php
namespace App\Domain\Approvisionnement\Repositories;

use App\Domain\Approvisionnement\Models\Fournisseur;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentFournisseurRepository extends BaseRepository implements FournisseurRepositoryInterface
{
    protected array $colonnesSearch = ['nom', 'contact', 'email', 'telephone'];
    protected array $filtresSimples = ['categorie'];

    public function __construct(Fournisseur $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->withCount('bonsCommande')
            ->when(!empty($filtres['search']), function ($q) use ($filtres) {
                $term = $filtres['search'];
                $q->where(fn($qq) => $qq
                    ->where('nom', 'like', "%{$term}%")
                    ->orWhere('contact', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                );
            })
            ->when(!empty($filtres['categorie']), fn($q) => $q->where('categorie', $filtres['categorie']))
            ->where('etat', 1)
            ->latest()
            ->paginate($parPage)
            ->withQueryString();
    }

    public function parCategorie(string $categorie): Collection
    {
        return $this->newQuery()->where('categorie', $categorie)->where('etat', 1)->get();
    }

    public function actifs(): Collection
    {
        return $this->newQuery()->where('etat', 1)->orderBy('nom')->get();
    }

    public function topFournisseurs(int $limite = 10): Collection
    {
        return $this->model->newQuery()
            ->where('etat', 1)
            ->withSum('bonsCommande as total_commandes', 'montant_total')
            ->orderByDesc('total_commandes')
            ->limit($limite)
            ->get();
    }

    public function statistiques(): array
    {
        return [
            'total'      => $this->model->where('etat', 1)->count(),
            'par_cat'    => $this->model->where('etat', 1)
                ->selectRaw('categorie, COUNT(*) as total')
                ->groupBy('categorie')
                ->pluck('total', 'categorie')
                ->toArray(),
            'note_moy'   => (float) $this->model->where('etat', 1)->avg('note_evaluation'),
        ];
    }
}