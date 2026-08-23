<?php

namespace App\Repositories\Eloquent;

use App\Models\ArticleBonCommande;
use App\Repositories\Interfaces\ArticleBonCommandeRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class ArticleBonCommandeRepository
 *
 * @package App\Repositories\Eloquent
 */
class ArticleBonCommandeRepository extends BaseRepository implements ArticleBonCommandeRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return ArticleBonCommande::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle ArticleBonCommande
     *
     * Exemple :
     * public function findByEmail(string $email): ?ArticleBonCommande
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}