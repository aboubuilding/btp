<?php

namespace App\Repositories\Eloquent;

use App\Models\DocumentEquipement;
use App\Repositories\Interfaces\DocumentEquipementRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class DocumentEquipementRepository
 *
 * @package App\Repositories\Eloquent
 */
class DocumentEquipementRepository extends BaseRepository implements DocumentEquipementRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return DocumentEquipement::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle DocumentEquipement
     *
     * Exemple :
     * public function findByEmail(string $email): ?DocumentEquipement
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}