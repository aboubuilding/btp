<?php

namespace App\Repositories\Eloquent;

use App\Models\CategorieMateriau;
use App\Repositories\Interfaces\CategorieMateriauRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class CategorieMateriauRepository
 *
 * @package App\Repositories\Eloquent
 */
class CategorieMateriauRepository extends BaseRepository implements CategorieMateriauRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return CategorieMateriau::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle CategorieMateriau
     *
     * Exemple :
     * public function findByEmail(string $email): ?CategorieMateriau
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}