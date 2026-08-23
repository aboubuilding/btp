<?php

namespace App\Repositories\Interfaces;

interface ProjetRepositoryInterface extends BaseRepositoryInterface
{
    public function getProjetsActifs(): int;
    public function getProjetsEnRetard(): int;
    public function getProjetsPlanifies(): int;
    public function getProjetsSuspendus(): int;
    public function getAvancementMoyen(): float;
    public function getTauxRetard(): float;
    public function getBudgetTotal(): float;
    public function getBudgetEngage(): float;
    public function getBudgetConsomme(): float;
    public function getRecentProjects(int $limit = 5): array;
    public function getProjetsByMonth(int $year, int $month): int;
    public function getBudgetByMonth(int $year, int $month): array;

    public function getProjectsByClient(int $clientId): array;
    public function countProjectsByClient(int $clientId): int;
}
