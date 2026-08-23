<?php

namespace App\Repositories\Eloquent;

use App\Models\Depense;
use App\Repositories\Interfaces\DepenseRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class DepenseRepository
 *
 * @package App\Repositories\Eloquent
 */
class DepenseRepository extends BaseRepository implements DepenseRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Depense::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Depense
     *
     * Exemple :
     * public function findByEmail(string $email): ?Depense
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}