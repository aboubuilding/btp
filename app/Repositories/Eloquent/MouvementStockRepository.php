<?php

namespace App\Repositories\Eloquent;

use App\Models\MouvementStock;
use App\Repositories\Interfaces\MouvementStockRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class MouvementStockRepository
 *
 * @package App\Repositories\Eloquent
 */
class MouvementStockRepository extends BaseRepository implements MouvementStockRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return MouvementStock::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle MouvementStock
     *
     * Exemple :
     * public function findByEmail(string $email): ?MouvementStock
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}