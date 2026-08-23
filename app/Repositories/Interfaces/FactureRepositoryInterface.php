<?php

namespace App\Repositories\Interfaces;

interface FactureRepositoryInterface extends BaseRepositoryInterface
{
    public function getFacturesEnRetard(): array;
    public function getTotalMontantRetard(): float;
    public function getNombreFacturesRetard(): int;
    public function getClientsConcernes(): int;
    public function getFacturesProchesEcheance(int $days = 3): array;
    public function getFacturesByMonth(int $year, int $month): float;
}
