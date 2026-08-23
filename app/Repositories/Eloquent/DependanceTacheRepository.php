<?php

namespace App\Repositories\Eloquent;

use App\Models\DependanceTache;
use App\Repositories\Interfaces\DependanceTacheRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class DependanceTacheRepository
 *
 * @package App\Repositories\Eloquent
 */
class DependanceTacheRepository extends BaseRepository implements DependanceTacheRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return DependanceTache::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle DependanceTache
     *
     * Exemple :
     * public function findByEmail(string $email): ?DependanceTache
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}