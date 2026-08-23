<?php

namespace App\Repositories\Interfaces;

interface EquipementRepositoryInterface extends BaseRepositoryInterface
{
    public function getEnPanne(): int;
    public function getEnMaintenance(): int;
    public function getDisponibles(): int;
    public function getEnService(): int;
    public function getTauxDisponibilite(): float;
    public function getDernieresMaintenances(int $limit = 5): array;
}
