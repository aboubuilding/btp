<?php

namespace App\Repositories\Interfaces;

interface LigneEcritureComptableRepositoryInterface extends BaseRepositoryInterface
{
    public function getLignesByEcriture(int $ecritureId): array;
    public function getTotalByEcriture(int $ecritureId): array;
    public function getSoldeByCompte(int $compteId, ?int $exerciceId = null): float;
}
