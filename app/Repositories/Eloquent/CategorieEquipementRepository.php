<?php

namespace App\Repositories\Eloquent;

use App\Models\CategorieEquipement;
use App\Repositories\Interfaces\CategorieEquipementRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class CategorieEquipementRepository
 *
 * @package App\Repositories\Eloquent
 */
class CategorieEquipementRepository extends BaseRepository implements CategorieEquipementRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return CategorieEquipement::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle CategorieEquipement
     *
     * Exemple :
     * public function findByEmail(string $email): ?CategorieEquipement
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}