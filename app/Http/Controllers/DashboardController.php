<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected AuthService $authService;
    protected DashboardService $dashboardService;

    public function __construct(
        AuthService $authService,
        DashboardService $dashboardService
    ) {
        $this->authService = $authService;
        $this->dashboardService = $dashboardService;
    }

    /**
     * Afficher le tableau de bord
     */
    public function index()
    {
        $user = $this->authService->getUser();
        $stats = $this->dashboardService->getAllStats();

        return view('dashboard', [
            'user' => $user,
            'projetsStats' => $stats['projets'],
            'budgetStats' => $stats['budget'],
            'stockAlertes' => $stats['stock'],
            'enginsStats' => $stats['equipements'],
            'echeancesPaie' => $stats['paie'],
            'facturesRetard' => $stats['factures'],
            'recentProjects' => $stats['projets_recents'],
        ]);
    }

    /**
     * Récupérer les données pour les graphiques
     */
    public function getChartData(Request $request)
    {
        $type = $request->input('type', 'projets');

        try {
            $data = $this->dashboardService->getChartData($type);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des données'
            ], 500);
        }
    }

    /**
     * Rafraîchir les données du dashboard
     */
    public function refresh()
    {
        try {
            $stats = $this->dashboardService->refresh();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rafraîchissement'
            ], 500);
        }
    }
}
