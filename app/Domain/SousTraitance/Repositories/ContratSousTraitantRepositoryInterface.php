<?php
namespace App\Domain\SousTraitance\Repositories;

use App\Domain\SousTraitance\Models\ContratSousTraitant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContratSousTraitantRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(ContratSousTraitant $contrat): ContratSousTraitant;
    public function enCours(): Collection;
    public function parProjet(int $projetId): Collection;
    public function parSousTraitant(int $soustraitantId): Collection;
    public function statistiques(): array;
    public function genererNumero(): string;
    public function montantTotalEngage(): float;
}