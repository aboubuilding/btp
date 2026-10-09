<?php
namespace App\Domain\Execution\Services;

use App\Domain\Execution\Models\Projet;
use App\Domain\Execution\Repositories\ProjetRepositoryInterface;
use App\Domain\Execution\Events\ChantierDemarre;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\{JournalService, ReferenceService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProjetService
{
    public function __construct(
        private ProjetRepositoryInterface $repo,
        private JournalService $journal,
        private ReferenceService $reference,
    ) {}

    /**
     * Crée un chantier avec lignes budgétaires par défaut.
     */
    public function creer(array $data, ?int $userId = null): Projet
    {
        return DB::transaction(function () use ($data, $userId) {
            $data['code'] = $data['code'] ?? $this->reference->chantier();

            $projet = $this->repo->create($data);

            foreach (['Matériaux', "Main d'œuvre", 'Matériel', 'Sous-traitance', 'Frais généraux'] as $libelle) {
                $projet->ligneBudgets()->create([
                    'libelle'       => $libelle,
                    'montant_prevu' => 0,
                    'montant_reel'  => 0,
                ]);
            }

            $this->journal->log('projet.cree', $projet, $userId);
            return $projet->fresh(['ligneBudgets']);
        });
    }

    public function mettreAJour(Projet $projet, array $data): Projet
    {
        return DB::transaction(function () use ($projet, $data) {
            $projet = $this->repo->update($projet, $data);
            $this->journal->log('projet.modifie', $projet);
            return $projet;
        });
    }

    /**
     * Démarre un chantier.
     */
    public function demarrer(Projet $projet): Projet
    {
        if ($projet->statut === 'en_cours') return $projet;

        return DB::transaction(function () use ($projet) {
            $projet = $this->repo->update($projet, [
                'statut'            => 'en_cours',
                'date_debut_reelle' => now(),
            ]);

            event(new ChantierDemarre($projet));
            $this->journal->log('projet.demarre', $projet);
            return $projet;
        });
    }

    public function suspendre(Projet $projet, string $motif = ''): Projet
    {
        $projet = $this->repo->update($projet, ['statut' => 'suspendu']);
        $this->journal->log('projet.suspendu', $projet, null, null, ['motif' => $motif]);
        return $projet;
    }

    public function reprendre(Projet $projet): Projet
    {
        return $this->repo->update($projet, ['statut' => 'en_cours']);
    }

    public function terminer(Projet $projet): Projet
    {
        return DB::transaction(function () use ($projet) {
            $projet = $this->repo->update($projet, [
                'statut'          => 'termine',
                'date_fin_reelle' => now(),
                'pourcentage_avancement' => 100,
            ]);
            $this->journal->log('projet.termine', $projet);
            return $projet;
        });
    }

    /**
     * Validation DG/DT de clôture.
     */
    public function cloturer(Projet $projet, int $userId): Projet
    {
        if ($projet->statut !== 'termine') {
            throw new RegleGestionException('Seul un chantier terminé peut être clos.');
        }
        $this->journal->log('projet.cloture', $projet, $userId);
        return $projet;
    }

    public function desactiver(Projet $projet): bool
    {
        if ($projet->statut === 'en_cours') {
            throw new RegleGestionException('Impossible de désactiver un chantier en cours.');
        }
        return $this->repo->desactiver($projet);
    }

    /**
     * Recalcule l'avancement pondéré par lignes DQE (RG-E05).
     */
    public function recalculerAvancement(Projet $projet): float
    {
        $avancement = $this->repo->recalculerAvancement($projet);
        $this->journal->log('projet.avancement_maj', $projet, null, null, ['valeur' => $avancement]);
        return $avancement;
    }

    /**
     * Impute un montant au budget réel du chantier.
     */
    public function imputerAuBudgetReel(Projet $projet, float $montant, ?string $ligneLibelle = null): void
    {
        DB::transaction(function () use ($projet, $montant, $ligneLibelle) {
            $this->repo->mettreAJourBudgetReel($projet, $montant);

            if ($ligneLibelle) {
                $ligne = $projet->ligneBudgets()->where('libelle', $ligneLibelle)->first();
                $ligne?->increment('montant_reel', $montant);
            }
        });
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(Projet $projet): Projet
    {
        return $this->repo->avecDetails($projet);
    }

    public function pourUtilisateur(int $userId): Collection
    {
        return $this->repo->pourUtilisateur($userId);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function statistiquesFinancieres(): array
    {
        return $this->repo->statistiquesFinancieres();
    }
}