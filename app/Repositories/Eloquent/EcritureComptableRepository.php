<?php

namespace App\Repositories\Eloquent;

use App\Models\EcritureComptable;
use App\Repositories\Interfaces\EcritureComptableRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class EcritureComptableRepository
 *
 * @package App\Repositories\Eloquent
 */
class EcritureComptableRepository extends BaseRepository implements EcritureComptableRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return EcritureComptable::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle EcritureComptable
     *
     * Exemple :
     * public function findByEmail(string $email): ?EcritureComptable
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}