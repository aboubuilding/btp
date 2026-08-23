<?php

namespace App\Repositories\Eloquent;

use App\Models\Fournisseur;
use App\Repositories\Interfaces\FournisseurRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class FournisseurRepository
 *
 * @package App\Repositories\Eloquent
 */
class FournisseurRepository extends BaseRepository implements FournisseurRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Fournisseur::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Fournisseur
     *
     * Exemple :
     * public function findByEmail(string $email): ?Fournisseur
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}