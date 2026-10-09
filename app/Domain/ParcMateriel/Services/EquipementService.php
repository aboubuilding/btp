<?php
namespace App\Domain\ParcMateriel\Services;

use App\Domain\ParcMateriel\Models\Equipement;
use App\Domain\ParcMateriel\Repositories\EquipementRepositoryInterface;
use App\Domain\Socle\Services\{JournalService, ReferenceService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EquipementService
{
    public function __construct(
        private EquipementRepositoryInterface $repo,
        private JournalService $journal,
        private ReferenceService $reference,
    ) {}

    public function creer(array $data): Equipement
    {
        $data['code'] = $data['code'] ?? $this->reference->equipement();
        $equipement = $this->repo->create($data);
        $this->journal->log('equipement.cree', $equipement);
        return $equipement;
    }

    public function mettreAJour(Equipement $equipement, array $data): Equipement
    {
        $equipement = $this->repo->update($equipement, $data);
        $this->journal->log('equipement.modifie', $equipement);
        return $equipement;
    }

    public function desactiver(Equipement $equipement): bool
    {
        return $this->repo->desactiver($equipement);
    }

    public function changerStatut(Equipement $equipement, string $statut): Equipement
    {
        $equipement = $this->repo->update($equipement, ['statut' => $statut]);
        $this->journal->log('equipement.statut_change', $equipement, null, null, ['nouveau' => $statut]);
        return $equipement;
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(Equipement $equipement): Equipement
    {
        return $this->repo->avecDetails($equipement);
    }

    public function disponibles(): Collection
    {
        return $this->repo->disponibles();
    }

    public function enPanne(): Collection
    {
        return $this->repo->enPanne();
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function tauxDisponibilite(): float
    {
        return $this->repo->tauxDisponibilite();
    }
}