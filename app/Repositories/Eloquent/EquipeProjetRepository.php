<?php

namespace App\Repositories\Eloquent;

use App\Models\EquipeProjet;
use App\Repositories\Interfaces\EquipeProjetRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class EquipeProjetRepository
 *
 * @package App\Repositories\Eloquent
 */
class EquipeProjetRepository extends BaseRepository implements EquipeProjetRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return EquipeProjet::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle EquipeProjet
     *
     * Exemple :
     * public function findByEmail(string $email): ?EquipeProjet
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}