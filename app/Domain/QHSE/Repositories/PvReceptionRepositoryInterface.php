<?php
namespace App\Domain\QHSE\Repositories;

use App\Domain\QHSE\Models\PvReception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PvReceptionRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(PvReception $pv): PvReception;
    public function parProjet(int $projetId): Collection;
    public function provisoires(int $projetId): Collection;
    public function definitifs(int $projetId): Collection;
    public function avecReservesOuvertes(): Collection;
    public function statistiques(): array;
    public function nombreReservesOuvertes(int $pvId): int;
}