<?php

namespace App\Repositories\Eloquent;

use App\Models\PeriodePaie;
use App\Repositories\Interfaces\PeriodePaieRepositoryInterface;
use Carbon\Carbon;

class PeriodePaieRepository extends BaseRepository implements PeriodePaieRepositoryInterface
{
    public function model(): string
    {
        return PeriodePaie::class;
    }

    public function getEcheancesProches(int $days = 7): array
    {
        $dateDebut = Carbon::now();
        $dateFin = Carbon::now()->addDays($days);

        return $this->activeQuery()
            ->where('statut', 'ouvert')
            ->whereBetween('date_fin', [$dateDebut, $dateFin])
            ->orderBy('date_fin', 'asc')
            ->get()
            ->toArray();
    }
}
