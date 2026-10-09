<?php
namespace App\Domain\QHSE\Services;

use App\Domain\QHSE\Models\IncidentSecurite;
use App\Domain\QHSE\Repositories\IncidentRepositoryInterface;
use App\Domain\QHSE\Events\IncidentGraveDeclare;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class IncidentService
{
    public function __construct(
        private IncidentRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    /**
     * RG-Q01 : incident grave → notification direction.
     */
    public function declarer(array $data, ?int $userId = null): IncidentSecurite
    {
        return DB::transaction(function () use ($data, $userId) {
            $data['declare_par'] = $userId ?? auth()->id();
            $data['statut']      = 'declare';

            $incident = $this->repo->create($data);

            if ($incident->est_grave) {
                event(new IncidentGraveDeclare($incident));
            }

            $this->journal->log('incident.declare', $incident);
            return $incident;
        });
    }

    public function mettreAJour(IncidentSecurite $incident, array $data): IncidentSecurite
    {
        $incident = $this->repo->update($incident, $data);
        $this->journal->log('incident.modifie', $incident);
        return $incident;
    }

    public function mettreEnAnalyse(IncidentSecurite $incident): IncidentSecurite
    {
        return $this->repo->update($incident, ['statut' => 'en_analyse']);
    }

    public function cloturer(IncidentSecurite $incident, ?string $actionsCorrectives = null): IncidentSecurite
    {
        $incident = $this->repo->update($incident, [
            'statut'              => 'clos',
            'actions_correctives' => $actionsCorrectives ?? $incident->actions_correctives,
        ]);
        $this->journal->log('incident.clos', $incident);
        return $incident;
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(IncidentSecurite $incident): IncidentSecurite
    {
        return $this->repo->avecDetails($incident);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function joursSansAccident(int $projetId): int
    {
        return $this->repo->joursSansAccident($projetId);
    }

    public function tauxFrequence(int $projetId, string $debut, string $fin): float
    {
        return $this->repo->tauxFrequence($projetId, $debut, $fin);
    }
}