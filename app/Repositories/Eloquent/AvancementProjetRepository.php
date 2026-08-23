<?php

namespace App\Repositories\Eloquent;

use App\Models\AvancementProjet;
use App\Repositories\Interfaces\AvancementProjetRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class AvancementProjetRepository
 *
 * @package App\Repositories\Eloquent
 */
class AvancementProjetRepository extends BaseRepository implements AvancementProjetRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return AvancementProjet::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle AvancementProjet
     *
     * Exemple :
     * public function findByEmail(string $email): ?AvancementProjet
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}