<?php

namespace App\Repositories\Eloquent;

use App\Models\ReleveCarburant;
use App\Repositories\Interfaces\ReleveCarburantRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class ReleveCarburantRepository
 *
 * @package App\Repositories\Eloquent
 */
class ReleveCarburantRepository extends BaseRepository implements ReleveCarburantRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return ReleveCarburant::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle ReleveCarburant
     *
     * Exemple :
     * public function findByEmail(string $email): ?ReleveCarburant
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}