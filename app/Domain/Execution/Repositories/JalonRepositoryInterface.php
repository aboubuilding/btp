<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\JalonProjet;
use Illuminate\Support\Collection;

interface JalonRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function parProjet(int $projetId): Collection;
    public function atteints(int $projetId): Collection;
    public function manques(): Collection;
    public function prochainsEcheances(int $jours = 30): Collection;
}