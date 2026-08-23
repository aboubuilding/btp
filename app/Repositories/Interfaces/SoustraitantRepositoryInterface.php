<?php

namespace App\Repositories\Interfaces;

interface SoustraitantRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveSoustraitants(): array;
    public function getSoustraitantsBySpecialite(string $specialite): array;
    public function getTopRated(int $limit = 5): array;
    public function updateStatus(int $id, string $status): bool;
    public function search(string $keyword): array;
    public function getStats(): array;
}
