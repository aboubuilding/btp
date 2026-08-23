<?php

namespace App\Repositories\Eloquent;

use App\Models\Paiement;
use App\Repositories\Interfaces\PaiementRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class PaiementRepository
 *
 * @package App\Repositories\Eloquent
 */
class PaiementRepository extends BaseRepository implements PaiementRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Paiement::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Paiement
     *
     * Exemple :
     * public function findByEmail(string $email): ?Paiement
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}