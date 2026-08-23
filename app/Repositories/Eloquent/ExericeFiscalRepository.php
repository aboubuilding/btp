<?php

namespace App\Repositories\Eloquent;

use App\Models\ExericeFiscal;
use App\Repositories\Interfaces\ExericeFiscalRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class ExericeFiscalRepository
 *
 * @package App\Repositories\Eloquent
 */
class ExericeFiscalRepository extends BaseRepository implements ExericeFiscalRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return ExericeFiscal::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle ExericeFiscal
     *
     * Exemple :
     * public function findByEmail(string $email): ?ExericeFiscal
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}