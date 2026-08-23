<?php

namespace App\Repositories\Eloquent;

use App\Models\NiveauStock;
use App\Repositories\Interfaces\NiveauStockRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class NiveauStockRepository
 *
 * @package App\Repositories\Eloquent
 */
class NiveauStockRepository extends BaseRepository implements NiveauStockRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return NiveauStock::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle NiveauStock
     *
     * Exemple :
     * public function findByEmail(string $email): ?NiveauStock
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}