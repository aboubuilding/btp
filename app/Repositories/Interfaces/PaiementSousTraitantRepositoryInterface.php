<?php

namespace App\Repositories\Interfaces;

interface PaiementSousTraitantRepositoryInterface extends BaseRepositoryInterface
{
    public function getPaiementsWithRelations(): array;
    public function getPaiementsByFacture(int $factureId): array;
    public function getPaiementsBySoustraitant(int $soustraitantId): array;
    public function search(string $keyword): array;
    public function getStats(): array;
    public function getTotalPaiementsByFacture(int $factureId): float;
}
