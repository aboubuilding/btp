<?php
namespace App\Domain\Personnel\Repositories;

use App\Domain\Personnel\Models\PeriodePaie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PeriodePaieRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecBulletins(PeriodePaie $periode): PeriodePaie;
    public function ouverte(): ?PeriodePaie;
    public function parType(string $type): Collection;
    public function statistiques(): array;
}