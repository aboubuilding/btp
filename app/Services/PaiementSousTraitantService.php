<?php

namespace App\Services;

use App\Models\PaiementSousTraitant;
use App\Repositories\Interfaces\PaiementSousTraitantRepositoryInterface;
use App\Repositories\Interfaces\FactureRepositoryInterface;
use App\Repositories\Interfaces\SoustraitantRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaiementSousTraitantService
{
    protected PaiementSousTraitantRepositoryInterface $repository;
    protected FactureRepositoryInterface $factureRepository;
    protected SoustraitantRepositoryInterface $soustraitantRepository;

    public function __construct(
        PaiementSousTraitantRepositoryInterface $repository,
        FactureRepositoryInterface $factureRepository,
        SoustraitantRepositoryInterface $soustraitantRepository
    ) {
        $this->repository = $repository;
        $this->factureRepository = $factureRepository;
        $this->soustraitantRepository = $soustraitantRepository;
    }

    public function getAll(): array
    {
        try {
            return $this->repository->getPaiementsWithRelations();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des paiements: ' . $e->getMessage());
            return [];
        }
    }

    public function getPaiement(int $id): ?PaiementSousTraitant
    {
        try {
            return $this->repository->withSupprime()
                ->with(['facture', 'facture.facturable'])
                ->find($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du paiement: ' . $e->getMessage());
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
                'montant_total' => 0,
                'par_mode' => [],
                'par_mois' => [],
            ];
        }
    }

    public function getFactures(): array
    {
        try {
            return $this->factureRepository->getFacturesPayables();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des factures: ' . $e->getMessage());
            return [];
        }
    }

    public function getModes(): array
    {
        return PaiementSousTraitant::getModes();
    }

    public function create(array $data): ?PaiementSousTraitant
    {
        try {
            DB::beginTransaction();

            $data['etat'] = 1;

            // Créer le paiement
            $paiement = $this->repository->create($data);

            if (!$paiement) {
                throw new \Exception('Erreur lors de la création du paiement');
            }

            // Mettre à jour le statut de la facture
            $this->updateFactureStatus($data['facture_sous_traitant_id']);

            DB::commit();
            return $paiement;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du paiement: ' . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            DB::beginTransaction();

            $oldPaiement = $this->getPaiement($id);
            if (!$oldPaiement) {
                throw new \Exception('Paiement non trouvé');
            }

            $oldFactureId = $oldPaiement->facture_sous_traitant_id;
            $updated = $this->repository->update($id, $data);

            if (!$updated) {
                throw new \Exception('Erreur lors de la mise à jour');
            }

            // Mettre à jour le statut de l'ancienne facture
            $this->updateFactureStatus($oldFactureId);

            // Mettre à jour le statut de la nouvelle facture si elle a changé
            if (isset($data['facture_sous_traitant_id']) && $data['facture_sous_traitant_id'] != $oldFactureId) {
                $this->updateFactureStatus($data['facture_sous_traitant_id']);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour du paiement: ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            DB::beginTransaction();

            $paiement = $this->getPaiement($id);
            if (!$paiement) {
                throw new \Exception('Paiement non trouvé');
            }

            $factureId = $paiement->facture_sous_traitant_id;
            $deleted = $this->repository->delete($id);

            if (!$deleted) {
                throw new \Exception('Erreur lors de la suppression');
            }

            // Mettre à jour le statut de la facture
            $this->updateFactureStatus($factureId);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du paiement: ' . $e->getMessage());
            return false;
        }
    }

    public function restore(int $id): bool
    {
        try {
            DB::beginTransaction();

            $restored = $this->repository->restore($id);

            if (!$restored) {
                throw new \Exception('Erreur lors de la restauration');
            }

            // Mettre à jour le statut de la facture
            $paiement = $this->getPaiement($id);
            if ($paiement) {
                $this->updateFactureStatus($paiement->facture_sous_traitant_id);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la restauration du paiement: ' . $e->getMessage());
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

    public function getPaiementsByFacture(int $factureId): array
    {
        try {
            return $this->repository->getPaiementsByFacture($factureId);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des paiements: ' . $e->getMessage());
            return [];
        }
    }

    public function getTotalPaiementsByFacture(int $factureId): float
    {
        try {
            return $this->repository->getTotalPaiementsByFacture($factureId);
        } catch (\Exception $e) {
            Log::error('Erreur lors du calcul du total: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Mettre à jour le statut d'une facture en fonction des paiements
     */
    private function updateFactureStatus(int $factureId): void
    {
        $facture = $this->factureRepository->find($factureId);
        if (!$facture) {
            return;
        }

        $totalPaye = $this->getTotalPaiementsByFacture($factureId);

        if ($totalPaye >= $facture->montant_ttc) {
            $statut = 'payee';
        } elseif ($totalPaye > 0) {
            $statut = 'partiellement_payee';
        } else {
            $statut = 'emise';
        }

        $this->factureRepository->update($factureId, ['statut' => $statut]);
    }
}
