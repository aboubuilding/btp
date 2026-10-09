<?php
namespace App\Domain\Commercial\Repositories;

use App\Domain\Commercial\Models\AvenantMarche;
use Illuminate\Support\Collection;

interface AvenantMarcheRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function parMarche(int $marcheId): Collection;
    public function signesParMarche(int $marcheId): Collection;
    public function montantSignesParMarche(int $marcheId): float;
    public function genererNumero(int $marcheId): string;
}