<?php

namespace App\Repositories\Interfaces;

interface PlanComptableRepositoryInterface extends BaseRepositoryInterface
{
    public function getRootAccounts(): array;
    public function getTree(): array;
    public function getByType(string $type): array;
    public function search(string $keyword): array;
    public function getStats(): array;
    public function getAvailableParents(int $excludeId = null): array;
    public function reorder(int $id, ?int $parentId): bool;
}
