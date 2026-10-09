<?php
namespace App\Domain\Personnel\Services;

use App\Domain\Personnel\Models\Presence;
use App\Domain\Personnel\Repositories\PresenceRepositoryInterface;
use App\Domain\Execution\Models\{EquipeProjet, Projet};
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\{JournalService, ParametreService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PresenceService
{
    public function __construct(
        private PresenceRepositoryInterface $repo,
        private JournalService $journal,
        private ParametreService $params,
    ) {}

    /**
     * RG-P01 : pointage en masse avec contrôle d'affectation.
     */
    public function pointer(int $projetId, string $date, array $lignes, int $userId): array
    {
        if (strtotime($date) > strtotime('today')) {
            throw new RegleGestionException('Pointage dans le futur interdit.');
        }

        return DB::transaction(function () use ($projetId, $date, $lignes, $userId) {
            $equipeIds = EquipeProjet::where('projet_id', $projetId)->pluck('employee_id')->all();
            $crees = [];

            foreach ($lignes as $ligne) {
                if (!in_array($ligne['employee_id'], $equipeIds, true)) {
                    throw new RegleGestionException(
                        "Employé {$ligne['employee_id']} non affecté au chantier (RG-P01)."
                    );
                }

                $heuresTravaillees = $this->calculerHeures(
                    $ligne['heure_arrivee'] ?? null,
                    $ligne['heure_depart'] ?? null
                );

                $crees[] = Presence::updateOrCreate(
                    [
                        'employee_id' => $ligne['employee_id'],
                        'date'        => $date,
                    ],
                    [
                        'projet_id'          => $projetId,
                        'heure_arrivee'      => $ligne['heure_arrivee'] ?? null,
                        'heure_depart'       => $ligne['heure_depart'] ?? null,
                        'heures_travaillees' => $heuresTravaillees,
                        'statut'             => $ligne['statut'] ?? 'present',
                        'enregistre_par'     => $userId,
                    ]
                );
            }

            $this->journal->log('presences.pointees', null, $userId, null, [
                'projet_id' => $projetId,
                'date'      => $date,
                'count'     => count($crees),
            ]);

            return $crees;
        });
    }

    /**
     * RG-P02 : valider les pointages d'une journée.
     */
    public function valider(int $projetId, string $date, int $userId): int
    {
        $count = Presence::where('projet_id', $projetId)
            ->whereDate('date', $date)
            ->whereNull('valide_le')
            ->update([
                'valide_par' => $userId,
                'valide_le'  => now(),
            ]);

        $this->journal->log('presences.validees', null, $userId, null, [
            'projet_id' => $projetId,
            'date'      => $date,
            'count'     => $count,
        ]);

        return $count;
    }

    public function paginate(array $filtres = [], int $parPage = 50): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function heuresTravaillees(int $employeId, string $debut, string $fin): float
    {
        return $this->repo->heuresTravaillees($employeId, $debut, $fin);
    }

    public function statistiques(int $projetId, string $debut, string $fin): array
    {
        return $this->repo->statistiques($projetId, $debut, $fin);
    }

    private function calculerHeures(?string $arrivee, ?string $depart): float
    {
        if (!$arrivee || !$depart) return 0;

        $a = strtotime($arrivee);
        $d = strtotime($depart);

        return round(max(0, $d - $a) / 3600, 2);
    }
}