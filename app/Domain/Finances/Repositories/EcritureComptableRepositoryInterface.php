<?php
namespace App\Domain\Finances\Repositories;

use App\Domain\Finances\Models\EcritureComptable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface EcritureComptableRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecLignes(EcritureComptable $ecriture): EcritureComptable;
    public function brouillons(): Collection;
    public function validees(): Collection;
    public function parExercice(int $exerciceId): Collection;
    public function parPeriode(string $debut, string $fin): Collection;
    public function balance(int $exerciceId): Collection;
    public function grandLivre(int $compteId, int $exerciceId): Collection;
    public function statistiques(?int $exerciceId = null): array;
}