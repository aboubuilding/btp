<?php
namespace App\Domain\ParcMateriel\Repositories;

use App\Domain\ParcMateriel\Models\PanneEquipement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PanneRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function parEquipement(int $equipementId): Collection;
    public function enCours(): Collection;
    public function nonCloturees(): Collection;
    public function coutTotal(): float;
    public function dureeMoyenneImmobilisation(): float;
    public function statistiques(): array;
}