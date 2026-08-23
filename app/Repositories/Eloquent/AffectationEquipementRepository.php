<?php

namespace App\Repositories\Eloquent;

use App\Models\AffectationEquipement;
use App\Repositories\Interfaces\AffectationEquipementRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class AffectationEquipementRepository
 *
 * @package App\Repositories\Eloquent
 */
class AffectationEquipementRepository extends BaseRepository implements AffectationEquipementRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return AffectationEquipement::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle AffectationEquipement
     *
     * Exemple :
     * public function findByEmail(string $email): ?AffectationEquipement
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}