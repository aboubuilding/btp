<?php
namespace App\Domain\ParcMateriel\Repositories;

use App\Domain\ParcMateriel\Models\MaintenanceEquipement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface MaintenanceRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function parEquipement(int $equipementId): Collection;
    public function preventives(int $equipementId): Collection;
    public function correctives(int $equipementId): Collection;
    public function echeanceProche(int $jours = 30): Collection;
    public function coutTotal(): float;
}