<?php

namespace App\Repositories\Eloquent;

use App\Models\ArticleDemandeAchat;
use App\Repositories\Interfaces\ArticleDemandeAchatRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class ArticleDemandeAchatRepository
 *
 * @package App\Repositories\Eloquent
 */
class ArticleDemandeAchatRepository extends BaseRepository implements ArticleDemandeAchatRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return ArticleDemandeAchat::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle ArticleDemandeAchat
     *
     * Exemple :
     * public function findByEmail(string $email): ?ArticleDemandeAchat
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}