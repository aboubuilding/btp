<?php
namespace App\Domain\SousTraitance\Services;

use App\Domain\SousTraitance\Models\Soustraitant;
use App\Domain\SousTraitance\Repositories\SoustraitantRepositoryInterface;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SoustraitantService
{
    public function __construct(
        private SoustraitantRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    public function creer(array $data): Soustraitant
    {
        $soustraitant = $this->repo->create($data);
        $this->journal->log('soustraitant.cree', $soustraitant);
        return $soustraitant;
    }

    public function mettreAJour(Soustraitant $soustraitant, array $data): Soustraitant
    {
        $soustraitant = $this->repo->update($soustraitant, $data);
        $this->journal->log('soustraitant.modifie', $soustraitant);
        return $soustraitant;
    }

    public function blacklister(Soustraitant $soustraitant): Soustraitant
    {
        $soustraitant = $this->repo->update($soustraitant, ['statut' => 'blackliste']);
        $this->journal->log('soustraitant.blackliste', $soustraitant);
        return $soustraitant;
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(Soustraitant $soustraitant): Soustraitant
    {
        return $this->repo->avecDetails($soustraitant);
    }

    public function actifs(): Collection
    {
        return $this->repo->actifs();
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }
}