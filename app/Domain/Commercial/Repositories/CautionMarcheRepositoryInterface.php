<?php
namespace App\Domain\Commercial\Repositories;

use App\Domain\Commercial\Models\CautionMarche;
use Illuminate\Support\Collection;

interface CautionMarcheRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function parMarche(int $marcheId): Collection;
    public function activesParMarche(int $marcheId): Collection;
    public function expirentBientot(int $jours = 30): Collection;
    public function montantTotalActif(int $marcheId): float;
}