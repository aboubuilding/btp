<?php
namespace App\Domain\Commercial\Repositories;

use App\Domain\Commercial\Models\Marche;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface MarcheRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(Marche $marche): Marche;
    public function actifs(): Collection;
    public function signes(): Collection;
    public function parClient(int $clientId): Collection;
    public function statistiques(): array;
    public function montantTotalPortefeuille(): float;
    public function genererReference(): string;
}