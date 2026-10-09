<?php
namespace App\Domain\Approvisionnement\Services;

use App\Domain\Approvisionnement\Models\Materiau;
use App\Domain\Approvisionnement\Repositories\MateriauRepositoryInterface;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MateriauService
{
    public function __construct(
        private MateriauRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    public function creer(array $data): Materiau
    {
        $materiau = $this->repo->create($data);
        $this->journal->log('materiau.cree', $materiau);
        return $materiau;
    }

    public function mettreAJour(Materiau $materiau, array $data): Materiau
    {
        $materiau = $this->repo->update($materiau, $data);
        $this->journal->log('materiau.modifie', $materiau);
        return $materiau;
    }

    public function desactiver(Materiau $materiau): bool
    {
        return $this->repo->desactiver($materiau);
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function sousSeuil(): Collection
    {
        return $this->repo->sousSeuil();
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }
}