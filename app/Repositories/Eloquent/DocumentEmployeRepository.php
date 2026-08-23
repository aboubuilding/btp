<?php

namespace App\Repositories\Eloquent;

use App\Models\DocumentEmploye;
use App\Repositories\Interfaces\DocumentEmployeRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class DocumentEmployeRepository
 *
 * @package App\Repositories\Eloquent
 */
class DocumentEmployeRepository extends BaseRepository implements DocumentEmployeRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return DocumentEmploye::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle DocumentEmploye
     *
     * Exemple :
     * public function findByEmail(string $email): ?DocumentEmploye
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}