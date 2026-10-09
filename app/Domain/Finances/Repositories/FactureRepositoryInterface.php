<?php
namespace App\Domain\Finances\Repositories;

use App\Domain\Finances\Models\Facture;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FactureRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(Facture $facture): Facture;
    public function clients(): Collection;
    public function fournisseurs(): Collection;
    public function impayees(): Collection;
    public function enRetard(): Collection;
    public function parProjet(int $projetId): Collection;
    public function parTiers(string $type, int $id): Collection;
    public function statistiques(): array;
    public function chiffreAffaire(string $debut, string $fin): float;
    public function encaisse(string $debut, string $fin): float;
    public function genererNumero(): string;
}