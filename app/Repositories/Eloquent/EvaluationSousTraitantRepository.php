<?php

namespace App\Repositories\Eloquent;

use App\Models\EvaluationSousTraitant;
use App\Repositories\Interfaces\EvaluationSousTraitantRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class EvaluationSousTraitantRepository
 *
 * @package App\Repositories\Eloquent
 */
class EvaluationSousTraitantRepository extends BaseRepository implements EvaluationSousTraitantRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return EvaluationSousTraitant::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle EvaluationSousTraitant
     *
     * Exemple :
     * public function findByEmail(string $email): ?EvaluationSousTraitant
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}