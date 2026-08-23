<?php

namespace App\Repositories\Eloquent;

use App\Models\Presence;
use App\Repositories\Interfaces\PresenceRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class PresenceRepository
 *
 * @package App\Repositories\Eloquent
 */
class PresenceRepository extends BaseRepository implements PresenceRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Presence::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Presence
     *
     * Exemple :
     * public function findByEmail(string $email): ?Presence
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}