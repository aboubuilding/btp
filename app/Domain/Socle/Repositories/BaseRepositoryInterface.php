<?php
namespace App\Domain\Socle\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /** Récupère un enregistrement par ID. */
    public function find(int $id): ?Model;

    /** Récupère un enregistrement par ID ou lève une exception. */
    public function findOrFail(int $id): Model;

    /** Récupère tous les enregistrements (optionnel : filtrés). */
    public function all(array $colonnes = ['*']): Collection;

    /** Pagine les résultats avec filtres. */
    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator;

    /** Crée un nouvel enregistrement. */
    public function create(array $data): Model;

    /** Met à jour un enregistrement. */
    public function update(Model $model, array $data): Model;

    /** Supprime physiquement un enregistrement. */
    public function delete(Model $model): bool;

    /** Désactive logiquement (etat = 0) — RG-X02. */
    public function desactiver(Model $model): bool;

    /** Réactive logiquement (etat = 1). */
    public function reactiver(Model $model): bool;

    /** Compte les enregistrements (optionnel : filtrés). */
    public function count(array $filtres = []): int;

    /** Vérifie l'existence selon des critères. */
    public function exists(array $criteria): bool;
}