<?php
namespace App\Domain\Personnel\Repositories;

use App\Domain\Personnel\Models\Contrat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContratRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function parEmploye(int $employeId): Collection;
    public function actifs(): Collection;
    public function expirentBientot(int $jours = 30): Collection;
    public function statistiques(): array;
    public function genererNumero(): string;
}