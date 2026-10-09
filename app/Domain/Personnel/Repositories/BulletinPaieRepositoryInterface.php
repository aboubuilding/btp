<?php
namespace App\Domain\Personnel\Repositories;

use App\Domain\Personnel\Models\BulletinPaie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BulletinPaieRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function parPeriode(int $periodeId): Collection;
    public function parEmploye(int $employeId): Collection;
    public function dansPeriode(int $employeId, int $periodeId): ?BulletinPaie;
    public function statistiques(?int $periodeId = null): array;
    public function masseSalarialePeriode(int $periodeId): array;
    public function genererNumero(): string;
}