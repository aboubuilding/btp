<?php
namespace App\Domain\Execution\Services;

use App\Domain\Execution\Models\{Projet, Tache, DependanceTache};
use App\Domain\Socle\Exceptions\RegleGestionException;
use Illuminate\Support\Collection;

class PlanningService
{
    /**
     * Détection de cycle dans les dépendances (RG-E04).
     */
    public function detecterCycle(Projet $projet): bool
    {
        $adj = [];
        foreach ($projet->taches as $tache) {
            $adj[$tache->id] = $tache->dependances()->pluck('depend_de_tache_id')->all();
        }

        $visites = [];
        $pile    = [];

        foreach (array_keys($adj) as $noeud) {
            if ($this->dfs($noeud, $adj, $visites, $pile)) {
                throw new RegleGestionException(
                    'Dépendance circulaire détectée dans les tâches (RG-E04).'
                );
            }
        }
        return false;
    }

    private function dfs(int $noeud, array $adj, array &$visites, array &$pile): bool
    {
        if (in_array($noeud, $pile, true)) return true;
        if (isset($visites[$noeud])) return false;

        $visites[$noeud] = true;
        $pile[] = $noeud;

        foreach ($adj[$noeud] ?? [] as $dep) {
            if ($this->dfs($dep, $adj, $visites, $pile)) return true;
        }
        array_pop($pile);
        return false;
    }

    /**
     * Ajoute une dépendance entre deux tâches (avec contrôle cycle).
     */
    public function ajouterDependance(Tache $tache, int $dependDeTacheId, string $type = 'fin_debut'): DependanceTache
    {
        if ($tache->id === $dependDeTacheId) {
            throw new RegleGestionException('Une tâche ne peut dépendre d\'elle-même.');
        }

        return \DB::transaction(function () use ($tache, $dependDeTacheId, $type) {
            $dep = DependanceTache::create([
                'tache_id'            => $tache->id,
                'depend_de_tache_id'  => $dependDeTacheId,
                'type'                => $type,
            ]);

            $this->detecterCycle($tache->projet);

            return $dep;
        });
    }

    /**
     * Tâches en retard pour un projet.
     */
    public function tachesEnRetard(Projet $projet): Collection
    {
        return $projet->taches()->enRetard()->with(['assigne', 'phase'])->get();
    }

    /**
     * Prochaines tâches planifiables (dépendances satisfaites).
     */
    public function tachesPlanifiables(Projet $projet): Collection
    {
        return $projet->taches()
            ->where('statut', 'a_faire')
            ->whereDoesntHave('dependances', function ($q) {
                $q->whereHas('dependDe', fn($qq) => $qq->where('statut', '!=', 'termine'));
            })
            ->with(['assigne', 'phase'])
            ->get();
    }

    /**
     * Structure pour Frappe Gantt.
     */
    public function dataPourGantt(Projet $projet): array
    {
        return $projet->taches()
            ->where('etat', 1)
            ->whereNotNull('date_debut')
            ->whereNotNull('date_fin')
            ->with(['phase', 'assigne', 'dependances'])
            ->orderBy('date_debut')
            ->get()
            ->map(fn($tache) => [
                'id'           => 'tache-' . $tache->id,
                'name'         => $tache->nom,
                'start'        => $tache->date_debut->format('Y-m-d'),
                'end'          => $tache->date_fin->format('Y-m-d'),
                'progress'     => (int) $tache->pourcentage_avancement,
                'dependencies' => $tache->dependances->map(fn($d) => 'tache-' . $d->depend_de_tache_id)->implode(', '),
                'custom_class' => $this->getTaskClass($tache),
                'url'          => route('projets.taches.edit', $tache),
            ])
            ->toArray();
    }

    private function getTaskClass(Tache $tache): string
    {
        if ($tache->est_en_retard) return 'task-retard';
        if ($tache->statut === 'termine') return 'task-termine';
        if ($tache->statut === 'en_cours') return 'task-en-cours';
        return 'task-a-faire';
    }
}