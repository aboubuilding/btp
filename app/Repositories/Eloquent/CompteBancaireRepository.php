<?php

namespace App\Repositories\Eloquent;

use App\Models\CompteBancaire;
use App\Repositories\Interfaces\CompteBancaireRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class CompteBancaireRepository
 *
 * @package App\Repositories\Eloquent
 */
class CompteBancaireRepository extends BaseRepository implements CompteBancaireRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return CompteBancaire::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle CompteBancaire
     *
     * Exemple :
     * public function findByEmail(string $email): ?CompteBancaire
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}