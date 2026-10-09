<?php
namespace App\Domain\Personnel\Services;

use App\Domain\Personnel\Models\Contrat;
use App\Domain\Personnel\Repositories\ContratRepositoryInterface;
use App\Domain\Socle\Services\{JournalService, ReferenceService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ContratService
{
    public function __construct(
        private ContratRepositoryInterface $repo,
        private JournalService $journal,
        private ReferenceService $reference,
    ) {}

    public function creer(array $data): Contrat
    {
        $data['numero'] = $data['numero'] ?? $this->reference->contrat();
        $contrat = $this->repo->create($data);
        $this->journal->log('contrat.cree', $contrat);
        return $contrat;
    }

    public function mettreAJour(Contrat $contrat, array $data): Contrat
    {
        $contrat = $this->repo->update($contrat, $data);
        $this->journal->log('contrat.modifie', $contrat);
        return $contrat;
    }

    public function resilier(Contrat $contrat): Contrat
    {
        $contrat = $this->repo->update($contrat, ['statut' => 'resilie']);
        $this->journal->log('contrat.resilie', $contrat);
        return $contrat;
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function expirentBientot(int $jours = 30): Collection
    {
        return $this->repo->expirentBientot($jours);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }
}