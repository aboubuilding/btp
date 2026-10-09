<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\PhaseProjet;
use Illuminate\Support\Collection;

interface PhaseRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function parProjet(int $projetId): Collection;
    public function ordonnees(int $projetId): Collection;
    public function avecTaches(int $projetId): Collection;
}