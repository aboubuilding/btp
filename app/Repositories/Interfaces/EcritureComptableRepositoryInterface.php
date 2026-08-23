<?php

namespace App\Repositories\Interfaces;

interface EcritureComptableRepositoryInterface extends BaseRepositoryInterface
{
    public function getEcrituresWithRelations(): array;
    public function getEcrituresByExercice(int $exerciceId): array;
    public function getEcrituresByStatut(string $statut): array;
    public function getEcrituresByDateRange(string $start, string $end): array;
    public function updateStatut(int $id, string $statut): bool;
    public function search(string $keyword): array;
    public function getStats(): array;
    public function generateNumero(): string;
}
