<?php

namespace App\Repositories\Interfaces;

interface LigneBudgetRepositoryInterface extends BaseRepositoryInterface
{
    public function getBudgetByCategorie(): array;
}
