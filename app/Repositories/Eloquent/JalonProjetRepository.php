<?php

namespace App\Repositories\Eloquent;

use App\Models\JalonProjet;
use App\Repositories\Interfaces\JalonProjetRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class JalonProjetRepository
 *
 * @package App\Repositories\Eloquent
 */
class JalonProjetRepository extends BaseRepository implements JalonProjetRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return JalonProjet::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle JalonProjet
     *
     * Exemple :
     * public function findByEmail(string $email): ?JalonProjet
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}