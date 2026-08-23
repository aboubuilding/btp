<?php

namespace App\Repositories\Eloquent;

use App\Models\DemandeConge;
use App\Repositories\Interfaces\DemandeCongeRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class DemandeCongeRepository
 *
 * @package App\Repositories\Eloquent
 */
class DemandeCongeRepository extends BaseRepository implements DemandeCongeRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return DemandeConge::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle DemandeConge
     *
     * Exemple :
     * public function findByEmail(string $email): ?DemandeConge
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}