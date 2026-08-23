<?php

namespace App\Repositories\Eloquent;

use App\Models\Entrepot;
use App\Repositories\Interfaces\EntrepotRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class EntrepotRepository
 *
 * @package App\Repositories\Eloquent
 */
class EntrepotRepository extends BaseRepository implements EntrepotRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Entrepot::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Entrepot
     *
     * Exemple :
     * public function findByEmail(string $email): ?Entrepot
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}