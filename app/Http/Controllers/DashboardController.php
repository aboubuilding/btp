<?php
namespace App\Http\Controllers;

use App\Domain\Execution\Repositories\ProjetRepositoryInterface;
use App\Domain\Finances\Repositories\FactureRepositoryInterface;
use App\Domain\Approvisionnement\Repositories\StockRepositoryInterface;
use App\Domain\QHSE\Repositories\IncidentRepositoryInterface;
use App\Domain\Execution\Models\Projet;

class DashboardController extends Controller
{
    public function __construct(
        private ProjetRepositoryInterface $projets,
        private FactureRepositoryInterface $factures,
        private StockRepositoryInterface $stock,
        private IncidentRepositoryInterface $incidents,
    ) {}

    public function index()
    {
        $user = auth()->user();

        $query = Projet::with(['client', 'marche'])->where('statut', 'en_cours');
        if ($user->hasRole('conducteur_travaux', 'chef_chantier')) {
            $query->whereIn('id', $this->projets->pourUtilisateur($user->id)->pluck('id'));
        }

        $stats = array_merge(
            $this->projets->statistiques(),
            $this->factures->statistiques(),
            $this->stock->statistiques(),
            ['incidents_mois' => $this->incidents->statistiques()['mois'] ?? 0]
        );

        return view('dashboard.index', [
            'stats'     => $stats,
            'chantiers' => $query->limit(6)->get(),
            'alertes'   => $this->stock->sousSeuil()->take(5),
        ]);
    }
}