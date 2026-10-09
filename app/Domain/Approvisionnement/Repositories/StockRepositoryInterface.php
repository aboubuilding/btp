<?php
namespace App\Domain\Approvisionnement\Repositories;

use App\Domain\Approvisionnement\Models\NiveauStock;
use Illuminate\Support\Collection;

interface StockRepositoryInterface
{
    public function niveauxAvecAlertes(array $filtres = []): Collection;
    public function niveauxParEntrepot(int $entrepotId): Collection;
    public function niveau(int $entrepotId, int $materiauId): ?NiveauStock;
    public function sousSeuil(): Collection;
    public function valeurTotale(): float;
    public function valeurParEntrepot(int $entrepotId): float;
    public function consommationParChantier(int $projetId): Collection;
    public function dernierCmup(int $entrepotId, int $materiauId): float;
    public function statistiques(): array;
}