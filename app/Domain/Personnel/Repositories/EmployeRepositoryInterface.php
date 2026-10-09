<?php
namespace App\Domain\Personnel\Repositories;

use App\Domain\Personnel\Models\Employe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface EmployeRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(Employe $employe): Employe;
    public function findByMatricule(string $matricule): ?Employe;
    public function actifs(): Collection;
    public function journaliers(): Collection;
    public function permanents(): Collection;
    public function parDepartement(int $departementId): Collection;
    public function parPoste(int $posteId): Collection;
    public function parChantier(int $projetId): Collection;
    public function statistiques(): array;
    public function masseSalariale(?int $departementId = null): float;
    public function genererMatricule(): string;
}