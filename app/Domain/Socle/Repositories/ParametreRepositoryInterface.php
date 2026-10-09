<?php
namespace App\Domain\Socle\Repositories;

use App\Domain\Socle\Models\Parametre;
use Illuminate\Support\Collection;

interface ParametreRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCle(string $cle): ?Parametre;
    public function getValeur(string $cle, mixed $default = null): mixed;
    public function setValeur(string $cle, mixed $valeur): Parametre;
    public function parGroupe(string $prefixe): Collection;
    public function tousActifs(): Collection;
}