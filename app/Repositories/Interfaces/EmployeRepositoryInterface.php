<?php

namespace App\Repositories\Interfaces;

interface EmployeRepositoryInterface extends BaseRepositoryInterface
{
    public function getEmployesActifsCount(): int;
    public function getTotalSalaires(): float;
}
