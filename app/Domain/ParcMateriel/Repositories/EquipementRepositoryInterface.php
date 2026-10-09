<?php
namespace App\Domain\ParcMateriel\Repositories;

use App\Domain\ParcMateriel\Models\Equipement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface EquipementRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(Equipement $equipement): Equipement;
    public function parCategorie(int $categorieId): Collection;
    public function disponibles(): Collection;
    public function enPanne(): Collection;
    public function avecDocumentsExpirant(int $jours = 30): Collection;
    public function statistiques(): array;
    public function tauxDisponibilite(): float;
    public function coutTotalParc(): float;
}