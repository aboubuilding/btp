<?php

namespace App\Repositories\Eloquent;

use App\Models\Departement;
use App\Repositories\Interfaces\DepartementRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class DepartementRepository
 *
 * @package App\Repositories\Eloquent
 */
class DepartementRepository extends BaseRepository implements DepartementRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Departement::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Departement
     *
     * Exemple :
     * public function findByEmail(string $email): ?Departement
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}