<?php
namespace App\Domain\Finances\Repositories;

use App\Domain\Finances\Models\Paiement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PaiementRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function encaissements(): Collection;
    public function decaissements(): Collection;
    public function parPeriode(string $debut, string $fin): Collection;
    public function pourFacture(int $factureId): Collection;
    public function statistiques(): array;
    public function totalEncaissements(string $debut, string $fin): float;
    public function totalDecaissements(string $debut, string $fin): float;
    public function genererNumero(): string;
}