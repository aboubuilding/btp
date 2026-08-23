<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function getModel(): Model;
    public function find(int $id): ?Model;
    public function findOrFail(int $id): Model;
    public function all(array $columns = ['*']): Collection;
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;
    public function count(): int;
    public function create(array $data): Model;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool; // etat = 2
    public function restore(int $id): bool; // etat = 1
    public function withSupprime(): Builder; // inclut les supprimés
    public function onlySupprime(): Builder; // uniquement les supprimés
    public function forceDelete(int $id): bool; // suppression physique
}
