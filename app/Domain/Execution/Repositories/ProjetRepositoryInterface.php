<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\Projet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProjetRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    // Lecture
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(Projet $projet): Projet;
    public function findByCode(string $code): ?Projet;
    public function pourUtilisateur(int $userId): Collection;
    public function enCours(): Collection;
    public function enRetard(): Collection;
    public function parClient(int $clientId): Collection;
    public function parConducteur(int $employeId): Collection;

    // Statistiques
    public function statistiques(): array;
    public function statistiquesParStatut(): array;
    public function statistiquesFinancieres(): array;
    public function montantTotalPortefeuille(): float;

    // Opérations métier
    public function genererCode(): string;
    public function recalculerAvancement(Projet $projet): float;
    public function mettreAJourBudgetReel(Projet $projet, float $montant): void;
}