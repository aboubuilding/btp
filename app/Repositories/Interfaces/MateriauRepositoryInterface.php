<?php

namespace App\Repositories\Interfaces;

interface MateriauRepositoryInterface extends BaseRepositoryInterface
{
    public function getAlertesStock(): array;
    public function getRupturesStock(): array;
    public function getTotalAlertes(): int;
    public function getTotalRuptures(): int;
    public function getCritiqueStock(): int;
}
