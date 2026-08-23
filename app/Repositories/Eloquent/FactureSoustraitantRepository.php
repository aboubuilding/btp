<?php

namespace App\Repositories\Eloquent;

use App\Models\FactureSoustraitant;
use App\Repositories\Interfaces\FactureSoustraitantRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class FactureSoustraitantRepository
 *
 * @package App\Repositories\Eloquent
 */
class FactureSoustraitantRepository extends BaseRepository implements FactureSoustraitantRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return FactureSoustraitant::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle FactureSoustraitant
     *
     * Exemple :
     * public function findByEmail(string $email): ?FactureSoustraitant
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}