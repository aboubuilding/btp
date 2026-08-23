<?php

namespace App\Repositories\Interfaces;

interface EvaluationSousTraitantRepositoryInterface extends BaseRepositoryInterface
{
    public function getEvaluationsWithRelations(): array;
    public function getEvaluationsBySoustraitant(int $soustraitantId): array;
    public function getEvaluationsByProjet(int $projetId): array;
    public function getLastEvaluations(int $limit = 10): array;
    public function search(string $keyword): array;
    public function getStats(): array;
    public function getMoyennesBySoustraitant(int $soustraitantId): array;
}
