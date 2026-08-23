<?php

namespace App\Repositories\Eloquent;

use App\Models\PlanComptable;
use App\Repositories\Interfaces\PlanComptableRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class PlanComptableRepository
 *
 * @package App\Repositories\Eloquent
 */
class PlanComptableRepository extends BaseRepository implements PlanComptableRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return PlanComptable::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle PlanComptable
     *
     * Exemple :
     * public function findByEmail(string $email): ?PlanComptable
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}