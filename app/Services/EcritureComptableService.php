<?php

namespace App\Services;

use App\Models\EcritureComptable;
use App\Repositories\Interfaces\EcritureComptableRepositoryInterface;
use App\Repositories\Interfaces\LigneEcritureComptableRepositoryInterface;
use App\Repositories\Interfaces\ExerciceFiscalRepositoryInterface;
use App\Repositories\Interfaces\PlanComptableRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\AuthService;

class EcritureComptableService
{
    protected EcritureComptableRepositoryInterface $repository;
    protected LigneEcritureComptableRepositoryInterface $ligneRepository;
    protected ExerciceFiscalRepositoryInterface $exerciceRepository;
    protected PlanComptableRepositoryInterface $planComptableRepository;
    protected AuthService $authService;

    public function __construct(
        EcritureComptableRepositoryInterface $repository,
        LigneEcritureComptableRepositoryInterface $ligneRepository,
        ExerciceFiscalRepositoryInterface $exerciceRepository,
        PlanComptableRepositoryInterface $planComptableRepository,
        AuthService $authService
    ) {
        $this->repository = $repository;
        $this->ligneRepository = $ligneRepository;
        $this->exerciceRepository = $exerciceRepository;
        $this->planComptableRepository = $planComptableRepository;
        $this->authService = $authService;
    }

    public function getAll(): array
    {
        try {
            return $this->repository->getEcrituresWithRelations();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des écritures: ' . $e->getMessage());
            return [];
        }
    }

    public function getEcriture(int $id): ?EcritureComptable
    {
        try {
            return $this->repository->withSupprime()
                ->with(['exerciceFiscal', 'createur', 'lignes.compte'])
                ->find($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'écriture: ' . $e->getMessage());
            return null;
        }
    }

    public function getLignesByEcriture(int $ecritureId): array
    {
        try {
            return $this->ligneRepository->getLignesByEcriture($ecritureId);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des lignes: ' . $e->getMessage());
            return [];
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
                'brouillons' => 0,
                'validees' => 0,
                'total_debit' => 0,
                'total_credit' => 0,
                'par_mois' => [],
            ];
        }
    }

    public function getExercices(): array
    {
        try {
            return $this->exerciceRepository->getActiveExercices();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des exercices: ' . $e->getMessage());
            return [];
        }
    }

    public function getComptes(): array
    {
        try {
            return $this->planComptableRepository->getTree();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des comptes: ' . $e->getMessage());
            return [];
        }
    }

    public function getStatuts(): array
    {
        return EcritureComptable::getStatuts();
    }

    public function getTypesReference(): array
    {
        return EcritureComptable::getTypesReference();
    }

    public function generateNumero(): string
    {
        return $this->repository->generateNumero();
    }

    public function create(array $data): ?EcritureComptable
    {
        try {
            DB::beginTransaction();

            // Générer le numéro d'écriture
            if (empty($data['numero_ecriture'])) {
                $data['numero_ecriture'] = $this->generateNumero();
            }

            // Ajouter l'utilisateur connecté
            $data['cree_par'] = $this->authService->getUserId();
            $data['etat'] = 1;

            // Créer l'écriture
            $ecriture = $this->repository->create($data);

            if (!$ecriture) {
                throw new \Exception('Erreur lors de la création de l\'écriture');
            }

            // Créer les lignes
            foreach ($data['lignes'] as $ligne) {
                $ligne['ecriture_comptable_id'] = $ecriture->id;
                $ligne['etat'] = 1;
                $this->ligneRepository->create($ligne);
            }

            DB::commit();
            return $ecriture;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de l\'écriture: ' . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            DB::beginTransaction();

            // Vérifier que l'écriture est un brouillon
            $ecriture = $this->getEcriture($id);
            if (!$ecriture || $ecriture->isValidee()) {
                throw new \Exception('Impossible de modifier une écriture validée');
            }

            // Mettre à jour l'écriture
            $updated = $this->repository->update($id, $data);

            if (!$updated) {
                throw new \Exception('Erreur lors de la mise à jour');
            }

            // Supprimer les anciennes lignes
            foreach ($ecriture->lignes as $ligne) {
                $this->ligneRepository->delete($ligne->id);
            }

            // Créer les nouvelles lignes
            foreach ($data['lignes'] as $ligne) {
                $ligne['ecriture_comptable_id'] = $id;
                $ligne['etat'] = 1;
                $this->ligneRepository->create($ligne);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour de l\'écriture: ' . $e->getMessage());
            return false;
        }
    }

    public function valider(int $id): bool
    {
        try {
            $ecriture = $this->getEcriture($id);
            if (!$ecriture) {
                return false;
            }

            if (!$ecriture->isEquilibree()) {
                Log::warning('Tentative de validation d\'une écriture non équilibrée: ' . $id);
                return false;
            }

            return $ecriture->valider();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la validation de l\'écriture: ' . $e->getMessage());
            return false;
        }
    }

    public function contrePasser(int $id): ?EcritureComptable
    {
        try {
            DB::beginTransaction();

            $ecriture = $this->getEcriture($id);
            if (!$ecriture) {
                throw new \Exception('Écriture non trouvée');
            }

            if (!$ecriture->isValidee()) {
                throw new \Exception('Impossible de contre-passer une écriture non validée');
            }

            $nouvelleEcriture = $ecriture->contrePasser();

            if (!$nouvelleEcriture) {
                throw new \Exception('Erreur lors de la contre-passation');
            }

            DB::commit();
            return $nouvelleEcriture;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la contre-passation: ' . $e->getMessage());
            return null;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $ecriture = $this->getEcriture($id);
            if (!$ecriture) {
                return false;
            }

            if ($ecriture->isValidee()) {
                Log::warning('Tentative de suppression d\'une écriture validée: ' . $id);
                return false;
            }

            // Supprimer les lignes
            foreach ($ecriture->lignes as $ligne) {
                $this->ligneRepository->delete($ligne->id);
            }

            return $this->repository->delete($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'écriture: ' . $e->getMessage());
            return false;
        }
    }

    public function restore(int $id): bool
    {
        try {
            return $this->repository->restore($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration de l\'écriture: ' . $e->getMessage());
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

    public function getGrandLivre(?int $exerciceId = null, ?int $compteId = null): array
    {
        try {
            $query = $this->ligneRepository->activeQuery()
                ->join('ecriture_comptables', 'ligne_ecriture_comptables.ecriture_comptable_id', '=', 'ecriture_comptables.id')
                ->join('plan_comptables', 'ligne_ecriture_comptables.compte_id', '=', 'plan_comptables.id')
                ->where('ecriture_comptables.statut', 'valide')
                ->select(
                    'ecriture_comptables.*',
                    'plan_comptables.code as compte_code',
                    'plan_comptables.nom as compte_nom',
                    'ligne_ecriture_comptables.debit',
                    'ligne_ecriture_comptables.credit',
                    'ligne_ecriture_comptables.description as ligne_description'
                )
                ->orderBy('ecriture_comptables.date_ecriture', 'asc');

            if ($exerciceId) {
                $query->where('ecriture_comptables.exercice_fiscal_id', $exerciceId);
            }

            if ($compteId) {
                $query->where('ligne_ecriture_comptables.compte_id', $compteId);
            }

            return $query->get()->toArray();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du grand livre: ' . $e->getMessage());
            return [];
        }
    }
}
