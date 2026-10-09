<?php
namespace App\Domain\Personnel\Repositories;

use App\Domain\Personnel\Models\Presence;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PresenceRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 50): LengthAwarePaginator;
    public function parJour(int $projetId, string $date): Collection;
    public function parEmploye(int $employeId, ?string $debut = null, ?string $fin = null): Collection;
    public function valideesPeriode(int $employeId, string $debut, string $fin): Collection;
    public function duJour(int $employeId, string $date): ?Presence;
    public function heuresTravaillees(int $employeId, string $debut, string $fin): float;
    public function heuresParProjet(int $projetId, string $debut, string $fin): Collection;
    public function aValider(int $projetId, ?string $date = null): Collection;
    public function statistiques(int $projetId, string $debut, string $fin): array;
}