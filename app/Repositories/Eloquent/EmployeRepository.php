<?php

namespace App\Repositories\Eloquent;

use App\Models\Employe;
use App\Repositories\Interfaces\EmployeRepositoryInterface;

class EmployeRepository extends BaseRepository implements EmployeRepositoryInterface
{
    public function model(): string
    {
        return Employe::class;
    }

    public function getEmployesActifsCount(): int
    {
        return $this->activeQuery()
            ->where('statut', 'actif')
            ->count();
    }

    public function getTotalSalaires(): float
    {
        return $this->activeQuery()
            ->where('statut', 'actif')
            ->sum('salaire_base');
    }
}
