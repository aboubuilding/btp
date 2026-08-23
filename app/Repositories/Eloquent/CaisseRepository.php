<?php

namespace App\Repositories\Eloquent;

use App\Models\Caisse;
use App\Repositories\Interfaces\CaisseRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class CaisseRepository
 *
 * @package App\Repositories\Eloquent
 */
class CaisseRepository extends BaseRepository implements CaisseRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Caisse::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Caisse
     *
     * Exemple :
     * public function findByEmail(string $email): ?Caisse
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}