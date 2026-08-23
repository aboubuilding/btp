<?php

namespace App\Services;

use App\Repositories\Interfaces\ProjetRepositoryInterface;
use App\Repositories\Interfaces\EmployeRepositoryInterface;
use App\Repositories\Interfaces\EquipementRepositoryInterface;
use App\Repositories\Interfaces\MateriauRepositoryInterface;
use App\Repositories\Interfaces\FactureRepositoryInterface;
use App\Repositories\Interfaces\PeriodePaieRepositoryInterface;
use App\Repositories\Interfaces\LigneBudgetRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    protected ProjetRepositoryInterface $projetRepository;
    protected EmployeRepositoryInterface $employeRepository;
    protected EquipementRepositoryInterface $equipementRepository;
    protected MateriauRepositoryInterface $materiauRepository;
    protected FactureRepositoryInterface $factureRepository;
    protected PeriodePaieRepositoryInterface $periodePaieRepository;
    protected LigneBudgetRepositoryInterface $ligneBudgetRepository;

    public function __construct(
        ProjetRepositoryInterface $projetRepository,
        EmployeRepositoryInterface $employeRepository,
        EquipementRepositoryInterface $equipementRepository,
        MateriauRepositoryInterface $materiauRepository,
        FactureRepositoryInterface $factureRepository,
        PeriodePaieRepositoryInterface $periodePaieRepository,
        LigneBudgetRepositoryInterface $ligneBudgetRepository
    ) {
        $this->projetRepository = $projetRepository;
        $this->employeRepository = $employeRepository;
        $this->equipementRepository = $equipementRepository;
        $this->materiauRepository = $materiauRepository;
        $this->factureRepository = $factureRepository;
        $this->periodePaieRepository = $periodePaieRepository;
        $this->ligneBudgetRepository = $ligneBudgetRepository;
    }

    /**
     * Récupérer toutes les statistiques du tableau de bord
     */
    public function getAllStats(): array
    {
        try {
            return [
                'projets' => $this->getProjetsStats(),
                'budget' => $this->getBudgetStats(),
                'stock' => $this->getStockAlertes(),
                'equipements' => $this->getEquipementsStats(),
                'paie' => $this->getPaieEcheances(),
                'factures' => $this->getFacturesRetard(),
                'projets_recents' => $this->getRecentProjects(),
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du dashboard: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    /**
     * Récupérer les statistiques des projets
     */
    public function getProjetsStats(): array
    {
        return [
            'actifs' => $this->projetRepository->getProjetsActifs(),
            'en_retard' => $this->projetRepository->getProjetsEnRetard(),
            'planifies' => $this->projetRepository->getProjetsPlanifies(),
            'suspendus' => $this->projetRepository->getProjetsSuspendus(),
            'avancement_moyen' => $this->projetRepository->getAvancementMoyen(),
            'taux_retard' => $this->projetRepository->getTauxRetard(),
        ];
    }

    /**
     * Récupérer les statistiques du budget
     */
    public function getBudgetStats(): array
    {
        $total = $this->projetRepository->getBudgetTotal();
        $consomme = $this->projetRepository->getBudgetConsomme();
        $engage = $this->projetRepository->getBudgetEngage();

        return [
            'total' => $total,
            'consomme' => $consomme,
            'engage' => $engage,
            'disponible' => max(0, $total - $consomme),
            'taux_consommation' => $total > 0 ? round(($consomme / $total) * 100, 1) : 0,
            'taux_engagement' => $total > 0 ? round(($engage / $total) * 100, 1) : 0,
            'par_categorie' => $this->ligneBudgetRepository->getBudgetByCategorie(),
        ];
    }

    /**
     * Récupérer les alertes stock
     */
    public function getStockAlertes(): array
    {
        return [
            'alertes' => $this->materiauRepository->getAlertesStock(),
            'ruptures' => $this->materiauRepository->getRupturesStock(),
            'total_alertes' => $this->materiauRepository->getTotalAlertes(),
            'total_ruptures' => $this->materiauRepository->getTotalRuptures(),
            'critique' => $this->materiauRepository->getCritiqueStock(),
        ];
    }

    /**
     * Récupérer les statistiques des équipements
     */
    public function getEquipementsStats(): array
    {
        return [
            'en_panne' => $this->equipementRepository->getEnPanne(),
            'en_maintenance' => $this->equipementRepository->getEnMaintenance(),
            'disponibles' => $this->equipementRepository->getDisponibles(),
            'en_service' => $this->equipementRepository->getEnService(),
            'total' => $this->equipementRepository->count(),
            'taux_disponibilite' => $this->equipementRepository->getTauxDisponibilite(),
            'dernieres_maintenances' => $this->equipementRepository->getDernieresMaintenances(5),
        ];
    }

    /**
     * Récupérer les échéances de paie
     */
    public function getPaieEcheances(): array
    {
        $echeances = $this->periodePaieRepository->getEcheancesProches(7);

        return [
            'echeances' => $echeances,
            'nombre_echeances' => count($echeances),
            'employes_a_payer' => $this->employeRepository->getEmployesActifsCount(),
            'total_salaires' => $this->employeRepository->getTotalSalaires(),
            'prochaine_echeance' => !empty($echeances) ? $echeances[0] : null,
        ];
    }

    /**
     * Récupérer les factures en retard
     */
    public function getFacturesRetard(): array
    {
        return [
            'en_retard' => $this->factureRepository->getFacturesEnRetard(),
            'total_retard' => $this->factureRepository->getNombreFacturesRetard(),
            'montant_total_retard' => $this->factureRepository->getTotalMontantRetard(),
            'clients_concernes' => $this->factureRepository->getClientsConcernes(),
            'proches_echeance' => $this->factureRepository->getFacturesProchesEcheance(),
            'total_proches' => count($this->factureRepository->getFacturesProchesEcheance()),
        ];
    }

    /**
     * Récupérer les projets récents
     */
    public function getRecentProjects(int $limit = 5): array
    {
        return $this->projetRepository->getRecentProjects($limit);
    }

    /**
     * Récupérer les données pour les graphiques
     */
    public function getChartData(string $type): array
    {
        try {
            return match ($type) {
                'projets' => $this->getProjetsChartData(),
                'budget' => $this->getBudgetChartData(),
                'factures' => $this->getFacturesChartData(),
                default => [],
            };
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des données graphiques: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Rafraîchir les données
     */
    public function refresh(): array
    {
        return $this->getAllStats();
    }

    /**
     * Données pour le graphique des projets
     */
    private function getProjetsChartData(): array
    {
        $labels = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $values[] = $this->projetRepository->getProjetsByMonth($date->year, $date->month);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * Données pour le graphique du budget
     */
    private function getBudgetChartData(): array
    {
        $labels = [];
        $prevue = [];
        $reel = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $data = $this->projetRepository->getBudgetByMonth($date->year, $date->month);
            $prevue[] = round($data['prevue'] / 1000000, 1);
            $reel[] = round($data['reel'] / 1000000, 1);
        }

        return [
            'labels' => $labels,
            'prevue' => $prevue,
            'reel' => $reel,
        ];
    }

    /**
     * Données pour le graphique des factures
     */
    private function getFacturesChartData(): array
    {
        $labels = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $values[] = round($this->factureRepository->getFacturesByMonth($date->year, $date->month) / 1000000, 1);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * Valeurs par défaut en cas d'erreur
     */
    private function getDefaultStats(): array
    {
        return [
            'projets' => [
                'actifs' => 0,
                'en_retard' => 0,
                'planifies' => 0,
                'suspendus' => 0,
                'avancement_moyen' => 0,
                'taux_retard' => 0,
            ],
            'budget' => [
                'total' => 0,
                'consomme' => 0,
                'engage' => 0,
                'disponible' => 0,
                'taux_consommation' => 0,
                'taux_engagement' => 0,
                'par_categorie' => [],
            ],
            'stock' => [
                'alertes' => [],
                'ruptures' => [],
                'total_alertes' => 0,
                'total_ruptures' => 0,
                'critique' => 0,
            ],
            'equipements' => [
                'en_panne' => 0,
                'en_maintenance' => 0,
                'disponibles' => 0,
                'en_service' => 0,
                'total' => 0,
                'taux_disponibilite' => 0,
                'dernieres_maintenances' => [],
            ],
            'paie' => [
                'echeances' => [],
                'nombre_echeances' => 0,
                'employes_a_payer' => 0,
                'total_salaires' => 0,
                'prochaine_echeance' => null,
            ],
            'factures' => [
                'en_retard' => [],
                'total_retard' => 0,
                'montant_total_retard' => 0,
                'clients_concernes' => 0,
                'proches_echeance' => [],
                'total_proches' => 0,
            ],
            'projets_recents' => [],
        ];
    }
}
