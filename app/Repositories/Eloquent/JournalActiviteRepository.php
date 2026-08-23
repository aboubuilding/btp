<?php

namespace App\Repositories\Eloquent;

use App\Models\JournalActivite;
use App\Repositories\Interfaces\JournalActiviteRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class JournalActiviteRepository
 *
 * @package App\Repositories\Eloquent
 */
class JournalActiviteRepository extends BaseRepository implements JournalActiviteRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return JournalActivite::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle JournalActivite
     *
     * Exemple :
     * public function findByEmail(string $email): ?JournalActivite
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}