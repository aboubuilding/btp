<?php

namespace App\Repositories\Interfaces;

interface PeriodePaieRepositoryInterface extends BaseRepositoryInterface
{
    public function getEcheancesProches(int $days = 7): array;
}
