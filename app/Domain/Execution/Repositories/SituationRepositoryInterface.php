<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\Situation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SituationRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(Situation $situation): Situation;
    public function parProjet(int $projetId): Collection;
    public function dernierePourProjet(int $projetId): ?Situation;
    public function prochainNumero(int $projetId): int;
    public function approuveesNonFacturees(): Collection;
    public function statistiques(): array;
    public function chiffreAffaireFacture(?int $projetId = null): float;
}