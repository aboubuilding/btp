<?php

namespace App\Repositories\Eloquent;

use App\Models\TransfertStock;
use App\Repositories\Interfaces\TransfertStockRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class TransfertStockRepository
 *
 * @package App\Repositories\Eloquent
 */
class TransfertStockRepository extends BaseRepository implements TransfertStockRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return TransfertStock::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle TransfertStock
     *
     * Exemple :
     * public function findByEmail(string $email): ?TransfertStock
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}