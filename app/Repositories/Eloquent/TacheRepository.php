<?php

namespace App\Repositories\Eloquent;

use App\Models\Tache;
use App\Repositories\Interfaces\TacheRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class TacheRepository
 *
 * @package App\Repositories\Eloquent
 */
class TacheRepository extends BaseRepository implements TacheRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Tache::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Tache
     *
     * Exemple :
     * public function findByEmail(string $email): ?Tache
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}