<?php
namespace App\Domain\ParcMateriel\Services;

use App\Domain\ParcMateriel\Models\{Equipement, PanneEquipement};
use App\Domain\ParcMateriel\Repositories\PanneRepositoryInterface;
use App\Domain\ParcMateriel\Events\PanneDeclaree;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PanneService
{
    public function __construct(
        private PanneRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    /**
     * RG-M03 : déclarer une panne passe l'engin en panne.
     */
    public function declarer(Equipement $equipement, array $data, ?int $userId = null): PanneEquipement
    {
        return DB::transaction(function () use ($equipement, $data, $userId) {
            $panne = $equipement->pannes()->create(array_merge($data, [
                'date_panne'  => $data['date_panne'] ?? now()->toDateString(),
                'declare_par' => $userId ?? auth()->id(),
                'statut'      => 'declaree',
            ]));

            $equipement->update(['statut' => 'en_panne']);

            event(new PanneDeclaree($panne));
            $this->journal->log('panne.declaree', $panne);

            return $panne;
        });
    }

    /**
     * RG-M03 : clôture de panne → disponible ou en_service.
     */
    public function cloturer(PanneEquipement $panne, ?float $coutReparation = null): PanneEquipement
    {
        if ($panne->est_cloturee) {
            throw new RegleGestionException('Panne déjà clôturée.');
        }

        return DB::transaction(function () use ($panne, $coutReparation) {
            $panne->update([
                'statut'          => 'cloturee',
                'date_cloture'    => now(),
                'cout_reparation' => $coutReparation ?? $panne->cout_reparation,
            ]);

            // Repasse disponible (ou en service si toujours affecté)
            $nouveauStatut = $panne->equipement->affectationOuverte()->exists()
                ? 'en_service'
                : 'disponible';

            $panne->equipement->update(['statut' => $nouveauStatut]);

            $this->journal->log('panne.cloturee', $panne);
            return $panne->fresh();
        });
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function enCours(): Collection
    {
        return $this->repo->enCours();
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }
}