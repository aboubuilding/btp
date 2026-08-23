<?php

namespace App\Repositories\Eloquent;

use App\Models\PaiementSousTraitant;
use App\Repositories\Interfaces\PaiementSousTraitantRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class PaiementSousTraitantRepository
 *
 * @package App\Repositories\Eloquent
 */
class PaiementSousTraitantRepository extends BaseRepository implements PaiementSousTraitantRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return PaiementSousTraitant::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle PaiementSousTraitant
     *
     * Exemple :
     * public function findByEmail(string $email): ?PaiementSousTraitant
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}