<?php
namespace App\Domain\Socle\Repositories;

use App\Domain\Socle\Models\JournalActivite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentJournalRepository extends BaseRepository implements JournalRepositoryInterface
{
    protected array $with = ['user'];
    protected string $orderBy = 'date_action';
    protected string $orderDir = 'desc';
    protected array $filtresSimples = ['user_id', 'action'];

    public function __construct(JournalActivite $model)
    {
        parent::__construct($model);
    }

    public function paginateRecents(array $filtres = [], int $parPage = 50): LengthAwarePaginator
    {
        $query = $this->newQuery()
            ->when(!empty($filtres['search']), fn($q, ) => $q->where('objet_type', 'like', "%{$filtres['search']}%"))
            ->when(!empty($filtres['action']), fn($q, ) => $q->where('action', $filtres['action']))
            ->when(!empty($filtres['user_id']), fn($q, ) => $q->where('user_id', $filtres['user_id']))
            ->when(!empty($filtres['date_debut']) && !empty($filtres['date_fin']),
                fn($q) => $q->whereBetween('date_action', [$filtres['date_debut'], $filtres['date_fin']])
            )
            ->orderBy('date_action', 'desc');

        return $query->paginate($parPage)->withQueryString();
    }

    public function pourObjet(string $type, int $id): Collection
    {
        return $this->newQuery()
            ->where('objet_type', $type)
            ->where('objet_id', $id)
            ->orderBy('date_action', 'desc')
            ->get();
    }

    public function pourUtilisateur(int $userId, int $limite = 100): Collection
    {
        return $this->newQuery()
            ->where('user_id', $userId)
            ->orderBy('date_action', 'desc')
            ->limit($limite)
            ->get();
    }

    public function archiver(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function purgerAvant(\DateTimeInterface $date): int
    {
        return DB::transaction(function () use ($date) {
            return $this->model->where('date_action', '<', $date)->delete();
        });
    }
}