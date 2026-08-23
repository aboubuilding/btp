<?php

namespace App\Repositories\Eloquent;

use App\Models\BulletinPaie;
use App\Repositories\Interfaces\BulletinPaieRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class BulletinPaieRepository
 *
 * @package App\Repositories\Eloquent
 */
class BulletinPaieRepository extends BaseRepository implements BulletinPaieRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return BulletinPaie::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle BulletinPaie
     *
     * Exemple :
     * public function findByEmail(string $email): ?BulletinPaie
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}