<?php

namespace App\Repositories\Eloquent;

use App\Models\TypeConge;
use App\Repositories\Interfaces\TypeCongeRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class TypeCongeRepository
 *
 * @package App\Repositories\Eloquent
 */
class TypeCongeRepository extends BaseRepository implements TypeCongeRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return TypeConge::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle TypeConge
     *
     * Exemple :
     * public function findByEmail(string $email): ?TypeConge
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}