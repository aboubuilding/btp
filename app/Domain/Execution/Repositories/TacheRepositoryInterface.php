<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\Tache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TacheRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function parProjet(int $projetId): Collection;
    public function parPhase(int $phaseId): Collection;
    public function enRetard(): Collection;
    public function enRetardParProjet(int $projetId): Collection;
    public function assigneesA(int $employeId): Collection;
    public function planifiables(int $projetId): Collection;
    public function statistiques(int $projetId): array;
    public function prochainesEcheances(int $jours = 7): Collection;
}