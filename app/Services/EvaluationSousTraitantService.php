<?php

namespace App\Services;

use App\Models\EvaluationSousTraitant;
use App\Repositories\Interfaces\EvaluationSousTraitantRepositoryInterface;
use App\Repositories\Interfaces\SoustraitantRepositoryInterface;
use App\Repositories\Interfaces\ProjetRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Services\AuthService;

class EvaluationSousTraitantService
{
    protected EvaluationSousTraitantRepositoryInterface $repository;
    protected SoustraitantRepositoryInterface $soustraitantRepository;
    protected ProjetRepositoryInterface $projetRepository;
    protected AuthService $authService;

    public function __construct(
        EvaluationSousTraitantRepositoryInterface $repository,
        SoustraitantRepositoryInterface $soustraitantRepository,
        ProjetRepositoryInterface $projetRepository,
        AuthService $authService
    ) {
        $this->repository = $repository;
        $this->soustraitantRepository = $soustraitantRepository;
        $this->projetRepository = $projetRepository;
        $this->authService = $authService;
    }

    public function getAll(): array
    {
        try {
            return $this->repository->getEvaluationsWithRelations();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des évaluations: ' . $e->getMessage());
            return [];
        }
    }

    public function getEvaluation(int $id): ?EvaluationSousTraitant
    {
        try {
            return $this->repository->withSupprime()
                ->with(['sousTraitant', 'projet', 'evaluateur'])
                ->find($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'évaluation: ' . $e->getMessage());
            return null;
        }
    }

    public function getStats(): array
    {
        try {
            return $this->repository->getStats();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques: ' . $e->getMessage());
            return [
                'total' => 0,
                'moyenne_qualite' => 0,
                'moyenne_delai' => 0,
                'moyenne_securite' => 0,
                'moyenne_generale' => 0,
                'par_mois' => [],
                'par_note' => ['qualite' => 0, 'delai' => 0, 'securite' => 0],
            ];
        }
    }

    public function getSoustraitants(): array
    {
        try {
            return $this->soustraitantRepository->getActiveSoustraitants();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des sous-traitants: ' . $e->getMessage());
            return [];
        }
    }

    public function getProjets(): array
    {
        try {
            return $this->projetRepository->getActiveProjects();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des projets: ' . $e->getMessage());
            return [];
        }
    }

    public function getEvaluationsBySoustraitant(int $soustraitantId): array
    {
        try {
            $evaluations = $this->repository->getEvaluationsBySoustraitant($soustraitantId);
            $moyennes = $this->repository->getMoyennesBySoustraitant($soustraitantId);
            $soustraitant = $this->soustraitantRepository->find($soustraitantId);

            return [
                'evaluations' => $evaluations,
                'moyennes' => $moyennes,
                'soustraitant' => $soustraitant,
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des évaluations: ' . $e->getMessage());
            return ['evaluations' => [], 'moyennes' => [], 'soustraitant' => null];
        }
    }

    public function create(array $data): ?EvaluationSousTraitant
    {
        try {
            // Ajouter l'utilisateur connecté comme évaluateur
            if (empty($data['evaluer_par'])) {
                $data['evaluer_par'] = $this->authService->getUserId();
            }

            $data['etat'] = 1;
            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'évaluation: ' . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'évaluation: ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'évaluation: ' . $e->getMessage());
            return false;
        }
    }

    public function restore(int $id): bool
    {
        try {
            return $this->repository->restore($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration de l\'évaluation: ' . $e->getMessage());
            return false;
        }
    }

    public function search(string $keyword): array
    {
        try {
            return $this->repository->search($keyword);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche: ' . $e->getMessage());
            return [];
        }
    }

    public function getLastEvaluations(int $limit = 10): array
    {
        try {
            return $this->repository->getLastEvaluations($limit);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des dernières évaluations: ' . $e->getMessage());
            return [];
        }
    }
}
