<?php

namespace App\Repositories\Interfaces;

interface FactureSoustraitantRepositoryInterface extends BaseRepositoryInterface
{
    public function getFacturesWithRelations(): array;
    public function getFacturesBySoustraitant(int $soustraitantId): array;
    public function getFacturesByProjet(int $projetId): array;
    public function getFacturesEnRetard(): array;
    public function updateStatus(int $id, string $status): bool;
    public function search(string $keyword): array;
    public function getStats(): array;
    public function generateNumeroFacture(): string;
}
