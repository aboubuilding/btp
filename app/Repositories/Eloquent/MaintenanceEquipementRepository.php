<?php

namespace App\Repositories\Eloquent;

use App\Models\MaintenanceEquipement;
use App\Repositories\Interfaces\MaintenanceEquipementRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class MaintenanceEquipementRepository
 *
 * @package App\Repositories\Eloquent
 */
class MaintenanceEquipementRepository extends BaseRepository implements MaintenanceEquipementRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return MaintenanceEquipement::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle MaintenanceEquipement
     *
     * Exemple :
     * public function findByEmail(string $email): ?MaintenanceEquipement
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}