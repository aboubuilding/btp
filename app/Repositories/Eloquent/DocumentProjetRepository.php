<?php

namespace App\Repositories\Eloquent;

use App\Models\DocumentProjet;
use App\Repositories\Interfaces\DocumentProjetRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class DocumentProjetRepository
 *
 * @package App\Repositories\Eloquent
 */
class DocumentProjetRepository extends BaseRepository implements DocumentProjetRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return DocumentProjet::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle DocumentProjet
     *
     * Exemple :
     * public function findByEmail(string $email): ?DocumentProjet
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}