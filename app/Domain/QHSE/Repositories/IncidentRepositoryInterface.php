<?php
namespace App\Domain\QHSE\Repositories;

use App\Domain\QHSE\Models\IncidentSecurite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IncidentRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(IncidentSecurite $incident): IncidentSecurite;
    public function parProjet(int $projetId): Collection;
    public function graves(): Collection;
    public function ouverts(): Collection;
    public function parPeriode(string $debut, string $fin): Collection;
    public function statistiques(): array;
    public function tauxFrequence(int $projetId, string $debut, string $fin): float;
    public function joursSansAccident(int $projetId): int;
}