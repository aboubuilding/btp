<?php

namespace App\Repositories\Eloquent;

use App\Models\Contrat;
use App\Repositories\Interfaces\ContratRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class ContratRepository
 *
 * @package App\Repositories\Eloquent
 */
class ContratRepository extends BaseRepository implements ContratRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Contrat::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Contrat
     *
     * Exemple :
     * public function findByEmail(string $email): ?Contrat
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}