<?php

namespace App\Repositories\Eloquent;

use App\Models\ArticleInventaire;
use App\Repositories\Interfaces\ArticleInventaireRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class ArticleInventaireRepository
 *
 * @package App\Repositories\Eloquent
 */
class ArticleInventaireRepository extends BaseRepository implements ArticleInventaireRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return ArticleInventaire::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle ArticleInventaire
     *
     * Exemple :
     * public function findByEmail(string $email): ?ArticleInventaire
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}