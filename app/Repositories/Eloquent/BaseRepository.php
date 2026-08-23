<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->model());
    }

    abstract public function model(): string;

    public function getModel(): Model
    {
        return $this->model;
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->activeQuery()->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->activeQuery()->paginate($perPage, $columns);
    }

    public function count(): int
    {
        return $this->activeQuery()->count();
    }

    public function find(int $id): ?Model
    {
        return $this->activeQuery()->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->activeQuery()->findOrFail($id);
    }

    public function create(array $data): Model
    {
        $data['etat'] = 1;
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->findOrFail($id);
        return $record->update($data);
    }

    public function delete(int $id): bool
    {
        $record = $this->findOrFail($id);
        return $record->update(['etat' => 2]);
    }

    public function restore(int $id): bool
    {
        $record = $this->onlySupprime()->findOrFail($id);
        return $record->update(['etat' => 1]);
    }

    public function withSupprime(): Builder
    {
        return $this->model->query();
    }

    public function onlySupprime(): Builder
    {
        return $this->model->where('etat', 2);
    }

    public function forceDelete(int $id): bool
    {
        $record = $this->withSupprime()->findOrFail($id);
        return (bool) $record->delete();
    }

    protected function activeQuery(): Builder
    {
        return $this->model->where('etat', 1);
    }
}
