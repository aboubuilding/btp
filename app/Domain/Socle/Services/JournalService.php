<?php
namespace App\Domain\Socle\Services;

use App\Domain\Socle\Repositories\JournalRepositoryInterface;
use App\Domain\Socle\Models\JournalActivite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class JournalService
{
    public function __construct(
        private JournalRepositoryInterface $repo,
    ) {}

    public function log(
        string $action,
        mixed $objet = null,
        ?int $userId = null,
        ?string $ip = null,
        array $meta = []
    ): JournalActivite {
        return $this->repo->create([
            'user_id'     => $userId ?? auth()->id(),
            'action'      => $action,
            'objet_type'  => $objet ? get_class($objet) : null,
            'objet_id'    => is_object($objet) ? ($objet->id ?? null) : null,
            'meta'        => $meta ?: null,
            'ip'          => $ip ?? request()->ip(),
            'date_action' => now(),
        ]);
    }

    public function paginateRecents(array $filtres = [], int $parPage = 50): LengthAwarePaginator
    {
        return $this->repo->paginateRecents($filtres, $parPage);
    }

    public function pourObjet(string $type, int $id): Collection
    {
        return $this->repo->pourObjet($type, $id);
    }

    public function pourUtilisateur(int $userId, int $limite = 100): Collection
    {
        return $this->repo->pourUtilisateur($userId, $limite);
    }

    public function purgerAvant(int $jours = 730): int
    {
        return $this->repo->purgerAvant(now()->subDays($jours));
    }
}