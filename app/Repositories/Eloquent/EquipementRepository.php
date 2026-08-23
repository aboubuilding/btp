<?php

namespace App\Repositories\Eloquent;

use App\Models\Equipement;
use App\Repositories\Interfaces\EquipementRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EquipementRepository extends BaseRepository implements EquipementRepositoryInterface
{
    public function model(): string
    {
        return Equipement::class;
    }

    public function getEnPanne(): int
    {
        return $this->activeQuery()
            ->where('statut', 'en_panne')
            ->count();
    }

    public function getEnMaintenance(): int
    {
        return $this->activeQuery()
            ->where('statut', 'en_maintenance')
            ->count();
    }

    public function getDisponibles(): int
    {
        return $this->activeQuery()
            ->where('statut', 'disponible')
            ->count();
    }

    public function getEnService(): int
    {
        return $this->activeQuery()
            ->where('statut', 'en_service')
            ->count();
    }

    public function getTauxDisponibilite(): float
    {
        $total = $this->count();
        $disponibles = $this->getDisponibles();
        $enService = $this->getEnService();
        return $total > 0 ? round((($disponibles + $enService) / $total) * 100, 1) : 0;
    }

    public function getDernieresMaintenances(int $limit = 5): array
    {
        return DB::table('equipements')
            ->join('maintenances_equipements', 'equipements.id', '=', 'maintenances_equipements.equipement_id')
            ->where('equipements.etat', 1)
            ->select(
                'equipements.nom as equipement_nom',
                'equipements.code',
                'maintenances_equipements.type',
                'maintenances_equipements.description',
                'maintenances_equipements.date',
                'maintenances_equipements.cout',
                'maintenances_equipements.prochaine_maintenance_date'
            )
            ->orderBy('maintenances_equipements.date', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
