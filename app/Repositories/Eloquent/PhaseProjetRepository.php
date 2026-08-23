<?php

namespace App\Repositories\Eloquent;

use App\Models\PhaseProjet;
use App\Repositories\Interfaces\PhaseProjetRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class PhaseProjetRepository
 *
 * @package App\Repositories\Eloquent
 */
class PhaseProjetRepository extends BaseRepository implements PhaseProjetRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return PhaseProjet::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle PhaseProjet
     *
     * Exemple :
     * public function findByEmail(string $email): ?PhaseProjet
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}