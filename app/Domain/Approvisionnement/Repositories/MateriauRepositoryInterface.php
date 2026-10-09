<?php
namespace App\Domain\Approvisionnement\Repositories;

use App\Domain\Approvisionnement\Models\Materiau;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface MateriauRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function parCategorie(int $categorieId): Collection;
    public function sousSeuil(): Collection;
    public function actifs(): Collection;
    public function statistiques(): array;
    public function valeurTotaleStock(): float;
    public function findByCode(string $code): ?Materiau;
}