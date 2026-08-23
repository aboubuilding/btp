<?php

namespace App\Repositories\Eloquent;

use App\Models\LigneEcritureComptable;
use App\Repositories\Interfaces\LigneEcritureComptableRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class LigneEcritureComptableRepository
 *
 * @package App\Repositories\Eloquent
 */
class LigneEcritureComptableRepository extends BaseRepository implements LigneEcritureComptableRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return LigneEcritureComptable::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle LigneEcritureComptable
     *
     * Exemple :
     * public function findByEmail(string $email): ?LigneEcritureComptable
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}