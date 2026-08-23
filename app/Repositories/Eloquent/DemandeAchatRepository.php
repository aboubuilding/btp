<?php

namespace App\Repositories\Eloquent;

use App\Models\DemandeAchat;
use App\Repositories\Interfaces\DemandeAchatRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class DemandeAchatRepository
 *
 * @package App\Repositories\Eloquent
 */
class DemandeAchatRepository extends BaseRepository implements DemandeAchatRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return DemandeAchat::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle DemandeAchat
     *
     * Exemple :
     * public function findByEmail(string $email): ?DemandeAchat
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}