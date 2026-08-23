<?php

namespace App\Repositories\Eloquent;

use App\Models\LigneBulletinPaie;
use App\Repositories\Interfaces\LigneBulletinPaieRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class LigneBulletinPaieRepository
 *
 * @package App\Repositories\Eloquent
 */
class LigneBulletinPaieRepository extends BaseRepository implements LigneBulletinPaieRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return LigneBulletinPaie::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle LigneBulletinPaie
     *
     * Exemple :
     * public function findByEmail(string $email): ?LigneBulletinPaie
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}