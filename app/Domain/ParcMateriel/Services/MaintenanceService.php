<?php
namespace App\Domain\ParcMateriel\Services;

use App\Domain\ParcMateriel\Models\{Equipement, MaintenanceEquipement};
use App\Domain\ParcMateriel\Repositories\MaintenanceRepositoryInterface;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MaintenanceService
{
    public function __construct(
        private MaintenanceRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    public function enregistrer(Equipement $equipement, array $data): MaintenanceEquipement
    {
        $maintenance = $equipement->maintenances()->create($data);

        if ($maintenance->type === 'preventive' && $equipement->statut === 'disponible') {
            $equipement->update(['statut' => 'en_maintenance']);
        }

        $this->journal->log('maintenance.enregistree', $maintenance);
        return $maintenance;
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function parEquipement(int $equipementId): Collection
    {
        return $this->repo->parEquipement($equipementId);
    }

    public function echeanceProche(int $jours = 30): Collection
    {
        return $this->repo->echeanceProche($jours);
    }

    public function coutTotal(): float
    {
        return $this->repo->coutTotal();
    }
}