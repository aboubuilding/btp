<?php
namespace App\Domain\Personnel\Services;

use App\Domain\Personnel\Models\Employe;
use App\Domain\Personnel\Repositories\EmployeRepositoryInterface;
use App\Domain\Socle\Services\{JournalService, ReferenceService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EmployeService
{
    public function __construct(
        private EmployeRepositoryInterface $repo,
        private JournalService $journal,
        private ReferenceService $reference,
    ) {}

    public function creer(array $data): Employe
    {
        $data['matricule'] = $data['matricule'] ?? $this->reference->employe();
        $employe = $this->repo->create($data);
        $this->journal->log('employe.cree', $employe);
        return $employe;
    }

    public function mettreAJour(Employe $employe, array $data): Employe
    {
        $employe = $this->repo->update($employe, $data);
        $this->journal->log('employe.modifie', $employe);
        return $employe;
    }

    public function desactiver(Employe $employe): bool
    {
        return $this->repo->update($employe, ['statut' => 'inactif', 'etat' => 0]);
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(Employe $employe): Employe
    {
        return $this->repo->avecDetails($employe);
    }

    public function actifs(): Collection
    {
        return $this->repo->actifs();
    }

    public function journaliers(): Collection
    {
        return $this->repo->journaliers();
    }

    public function parChantier(int $projetId): Collection
    {
        return $this->repo->parChantier($projetId);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function masseSalariale(?int $departementId = null): float
    {
        return $this->repo->masseSalariale($departementId);
    }
}