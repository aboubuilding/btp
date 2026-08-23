<?php

namespace App\Repositories\Eloquent;

use App\Models\ContratSousTraitant;
use App\Repositories\Interfaces\ContratSousTraitantRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class ContratSousTraitantRepository
 *
 * @package App\Repositories\Eloquent
 */
class ContratSousTraitantRepository extends BaseRepository implements ContratSousTraitantRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return ContratSousTraitant::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle ContratSousTraitant
     *
     * Exemple :
     * public function findByEmail(string $email): ?ContratSousTraitant
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}