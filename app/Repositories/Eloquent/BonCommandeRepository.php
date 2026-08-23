<?php

namespace App\Repositories\Eloquent;

use App\Models\BonCommande;
use App\Repositories\Interfaces\BonCommandeRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class BonCommandeRepository
 *
 * @package App\Repositories\Eloquent
 */
class BonCommandeRepository extends BaseRepository implements BonCommandeRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return BonCommande::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle BonCommande
     *
     * Exemple :
     * public function findByEmail(string $email): ?BonCommande
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}