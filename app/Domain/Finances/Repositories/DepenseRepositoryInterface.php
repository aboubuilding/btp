<?php
namespace App\Domain\Finances\Repositories;

use App\Domain\Finances\Models\Depense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DepenseRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(Depense $depense): Depense;
    public function enAttente(): Collection;
    public function approuvees(): Collection;
    public function parProjet(int $projetId): Collection;
    public function parCategorie(string $categorie): Collection;
    public function parPeriode(string $debut, string $fin): Collection;
    public function statistiques(): array;
    public function totalParProjet(int $projetId): float;
    public function totalParCategorie(?int $projetId = null): array;
}