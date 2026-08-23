<?php

namespace App\Repositories\Eloquent;

use App\Models\AvanceSalaire;
use App\Repositories\Interfaces\AvanceSalaireRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class AvanceSalaireRepository
 *
 * @package App\Repositories\Eloquent
 */
class AvanceSalaireRepository extends BaseRepository implements AvanceSalaireRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return AvanceSalaire::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle AvanceSalaire
     *
     * Exemple :
     * public function findByEmail(string $email): ?AvanceSalaire
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}