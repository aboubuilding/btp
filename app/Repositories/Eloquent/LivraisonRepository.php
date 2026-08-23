<?php

namespace App\Repositories\Eloquent;

use App\Models\Livraison;
use App\Repositories\Interfaces\LivraisonRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class LivraisonRepository
 *
 * @package App\Repositories\Eloquent
 */
class LivraisonRepository extends BaseRepository implements LivraisonRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Livraison::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Livraison
     *
     * Exemple :
     * public function findByEmail(string $email): ?Livraison
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}