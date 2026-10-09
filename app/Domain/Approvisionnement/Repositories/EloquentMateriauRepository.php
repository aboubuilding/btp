<?php
namespace App\Domain\Approvisionnement\Repositories;

use App\Domain\Approvisionnement\Models\Materiau;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentMateriauRepository extends BaseRepository implements MateriauRepositoryInterface
{
    protected array $with = ['categorie'];
    protected array $colonnesSearch = ['nom', 'code'];
    protected array $filtresSimples = ['categorie_id'];

    public function __construct(Materiau $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function parCategorie(int $categorieId): Collection
    {
        return $this->newQuery()->where('categorie_id', $categorieId)->get();
    }

    public function sousSeuil(): Collection
    {
        return $this->newQuery()->sousSeuil()->get();
    }

    public function actifs(): Collection
    {
        return $this->newQuery()->where('etat', 1)->orderBy('nom')->get();
    }

    public function statistiques(): array
    {
        return [
            'total'       => $this->model->where('etat', 1)->count(),
            'sous_seuil'  => $this->sousSeuil()->count(),
            'categories'  => $this->model->where('etat', 1)->distinct('categorie_id')->count('categorie_id'),
        ];
    }

    public function valeurTotaleStock(): float
    {
        return (float) DB::table('niveau_stocks')
            ->where('quantite', '>', 0)
            ->sum(DB::raw('quantite * cmup'));
    }

    public function findByCode(string $code): ?Materiau
    {
        return $this->newQuery()->where('code', $code)->first();
    }
}