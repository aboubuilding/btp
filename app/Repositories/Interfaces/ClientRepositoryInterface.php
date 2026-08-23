<?php

namespace App\Repositories\Interfaces;

interface ClientRepositoryInterface extends BaseRepositoryInterface
{
    public function getClientsWithProjects(): array;
    public function getActiveClients(): array;
    public function getTopClients(int $limit = 5): array;
    public function toggleActive(int $id): bool;
    public function search(string $keyword): array;
    public function getStats(): array;
}
