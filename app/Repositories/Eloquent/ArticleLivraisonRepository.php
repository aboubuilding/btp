<?php

namespace App\Repositories\Eloquent;

use App\Models\ArticleLivraison;
use App\Repositories\Interfaces\ArticleLivraisonRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class ArticleLivraisonRepository
 *
 * @package App\Repositories\Eloquent
 */
class ArticleLivraisonRepository extends BaseRepository implements ArticleLivraisonRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return ArticleLivraison::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle ArticleLivraison
     *
     * Exemple :
     * public function findByEmail(string $email): ?ArticleLivraison
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}