<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\{Projet, EquipeProjet};
use App\Domain\Personnel\Models\Employe;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentProjetRepository extends BaseRepository implements ProjetRepositoryInterface
{
    protected array $with = ['client', 'marche', 'conducteur'];
    protected array $colonnesSearch = ['nom', 'code', 'ville'];
    protected array $filtresSimples = ['statut', 'type', 'client_id', 'conducteur_travaux_id'];

    public function __construct(Projet $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecDetails(Projet $projet): Projet
    {
        return $projet->load([
            'client', 'marche', 'conducteur', 'chefChantier',
            'phases.taches', 'taches.assigne', 'jalons',
            'ligneBudgets', 'avancements', 'equipe.poste',
        ]);
    }

    public function findByCode(string $code): ?Projet
    {
        return $this->newQuery()->where('code', $code)->first();
    }

    public function pourUtilisateur(int $userId): Collection
    {
        $employe = Employe::where('user_id', $userId)->first();
        if (!$employe) return collect();

        return $this->newQuery()
            ->where(fn($q) => $q
                ->where('conducteur_travaux_id', $employe->id)
                ->orWhere('chef_chantier_id', $employe->id)
                ->orWhereHas('equipe', fn($qq) => $qq->where('employee_id', $employe->id))
            )
            ->orderByDesc('created_at')
            ->get();
    }

    public function enCours(): Collection
    {
        return $this->newQuery()->where('statut', 'en_cours')->get();
    }

    public function enRetard(): Collection
    {
        return $this->newQuery()
            ->where('statut', 'en_cours')
            ->whereDate('date_fin_prevue', '<', now())
            ->where('pourcentage_avancement', '<', 100)
            ->get();
    }

    public function parClient(int $clientId): Collection
    {
        return $this->newQuery()->where('client_id', $clientId)->latest()->get();
    }

    public function parConducteur(int $employeId): Collection
    {
        return $this->newQuery()
            ->where('conducteur_travaux_id', $employeId)
            ->orWhere('chef_chantier_id', $employeId)
            ->latest()
            ->get();
    }

    public function statistiques(): array
    {
        $totalBudget = $this->model->where('etat', 1)->sum('budget_prevu');
        $totalReel = $this->model->where('etat', 1)->sum('budget_reel');

        return [
            'chantiers_actifs'     => $this->model->where('statut', 'en_cours')->count(),
            'chantiers_planifies'  => $this->model->where('statut', 'planifie')->count(),
            'chantiers_termines'   => $this->model->where('statut', 'termine')->count(),
            'chantiers_suspendus'  => $this->model->where('statut', 'suspendu')->count(),
            'chantiers_annules'    => $this->model->where('statut', 'annule')->count(),
            'chantiers_retard'     => $this->model->where('statut', 'en_cours')
                ->whereDate('date_fin_prevue', '<', now())
                ->count(),
            'budget_total'         => (float) $totalBudget,
            'budget_reel_total'    => (float) $totalReel,
            'ecart_budget'         => (float) $totalBudget - (float) $totalReel,
        ];
    }

    public function statistiquesParStatut(): array
    {
        return $this->model->select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();
    }

    public function statistiquesFinancieres(): array
    {
        $parType = $this->model->where('etat', 1)
            ->select('type', DB::raw('SUM(budget_prevu) as budget'), DB::raw('SUM(budget_reel) as reel'))
            ->groupBy('type')
            ->get()
            ->mapWithKeys(fn($row) => [$row->type => [
                'budget' => (float) $row->budget,
                'reel'   => (float) $row->reel,
            ]])
            ->toArray();

        return [
            'par_type'      => $parType,
            'total_prevu'   => (float) $this->model->where('etat', 1)->sum('budget_prevu'),
            'total_reel'    => (float) $this->model->where('etat', 1)->sum('budget_reel'),
            'total_contrat' => (float) $this->model->where('etat', 1)->sum('montant_contrat'),
        ];
    }

    public function montantTotalPortefeuille(): float
    {
        return (float) $this->model->where('etat', 1)
            ->whereIn('statut', ['en_cours', 'planifie'])
            ->sum('montant_contrat');
    }

    public function genererCode(): string
    {
        $prefix = 'CH';
        $last = $this->model->whereYear('created_at', now()->year)->count();
        return sprintf('%s-%s-%03d', $prefix, now()->year, $last + 1);
    }

    public function recalculerAvancement(Projet $projet): float
    {
        $avancement = $projet->avancement_pondere;
        $projet->update(['pourcentage_avancement' => (int) $avancement]);
        return $avancement;
    }

    public function mettreAJourBudgetReel(Projet $projet, float $montant): void
    {
        $projet->increment('budget_reel', $montant);
    }
}