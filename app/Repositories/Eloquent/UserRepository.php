<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class UserRepository
 *
 * @package App\Repositories\Eloquent
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle User
     *
     * Exemple :
     * public function findByEmail(string $email): ?User
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}