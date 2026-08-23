<?php

namespace App\Repositories\Interfaces;

interface ContratSousTraitantRepositoryInterface extends BaseRepositoryInterface
{
    public function getContratsWithRelations(): array;
    public function getContratsBySousTraitant(int $sousTraitantId): array;
    public function getContratsByProjet(int $projetId): array;
    public function getContratsEnCours(): array;
    public function updateStatus(int $id, string $status): bool;
    public function search(string $keyword): array;
    public function getStats(): array;
    public function generateNumeroContrat(): string;
}
