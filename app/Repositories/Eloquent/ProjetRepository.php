<?php

namespace App\Repositories\Eloquent;

use App\Models\Projet;
use App\Repositories\Interfaces\ProjetRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProjetRepository extends BaseRepository implements ProjetRepositoryInterface
{
    public function model(): string
    {
        return Projet::class;
    }

    public function getProjetsActifs(): int
    {
        return $this->activeQuery()
            ->where('statut', 'en_cours')
            ->count();
    }

    public function getProjetsEnRetard(): int
    {
        return $this->activeQuery()
            ->where('statut', 'en_cours')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->count();
    }

    public function getProjetsPlanifies(): int
    {
        return $this->activeQuery()
            ->where('statut', 'planifie')
            ->count();
    }

    public function getProjetsSuspendus(): int
    {
        return $this->activeQuery()
            ->where('statut', 'suspendu')
            ->count();
    }

    public function getAvancementMoyen(): float
    {
        return round($this->activeQuery()
            ->where('statut', 'en_cours')
            ->avg('pourcentage_avancement') ?? 0, 1);
    }

    public function getTauxRetard(): float
    {
        $actifs = $this->getProjetsActifs();
        $retard = $this->getProjetsEnRetard();
        return $actifs > 0 ? round(($retard / $actifs) * 100, 1) : 0;
    }

    public function getBudgetTotal(): float
    {
        return $this->activeQuery()->sum('budget_prevue');
    }

    public function getBudgetEngage(): float
    {
        return $this->activeQuery()->sum('montant_contrat');
    }

    public function getBudgetConsomme(): float
    {
        return $this->activeQuery()->sum('budget_reel');
    }

    public function getRecentProjects(int $limit = 5): array
    {
        return $this->activeQuery()
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getProjetsByMonth(int $year, int $month): int
    {
        return $this->activeQuery()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();
    }

    public function getBudgetByMonth(int $year, int $month): array
    {
        $prevue = $this->activeQuery()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('budget_prevue');

        $reel = $this->activeQuery()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('budget_reel');

        return [
            'prevue' => $prevue,
            'reel' => $reel,
        ];
    }

    public function getProjectsByClient(int $clientId): array
    {
        return $this->activeQuery()
            ->where('client_id', $clientId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function countProjectsByClient(int $clientId): int
    {
        return $this->activeQuery()
            ->where('client_id', $clientId)
            ->count();
    }
}
