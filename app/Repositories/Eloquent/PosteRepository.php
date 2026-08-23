<?php

namespace App\Repositories\Eloquent;

use App\Models\Poste;
use App\Repositories\Interfaces\PosteRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class PosteRepository
 *
 * @package App\Repositories\Eloquent
 */
class PosteRepository extends BaseRepository implements PosteRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Poste::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Poste
     *
     * Exemple :
     * public function findByEmail(string $email): ?Poste
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}