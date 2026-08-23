<?php

namespace App\Repositories\Eloquent;

use App\Models\Inventaire;
use App\Repositories\Interfaces\InventaireRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class InventaireRepository
 *
 * @package App\Repositories\Eloquent
 */
class InventaireRepository extends BaseRepository implements InventaireRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Inventaire::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Inventaire
     *
     * Exemple :
     * public function findByEmail(string $email): ?Inventaire
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}