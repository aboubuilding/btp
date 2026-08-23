<?php

namespace App\Repositories\Eloquent;

use App\Models\PanneEquipement;
use App\Repositories\Interfaces\PanneEquipementRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class PanneEquipementRepository
 *
 * @package App\Repositories\Eloquent
 */
class PanneEquipementRepository extends BaseRepository implements PanneEquipementRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return PanneEquipement::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle PanneEquipement
     *
     * Exemple :
     * public function findByEmail(string $email): ?PanneEquipement
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}