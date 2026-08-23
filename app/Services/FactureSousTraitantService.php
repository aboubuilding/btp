<?php

namespace App\Services;

use App\Models\Facture;
use App\Repositories\Interfaces\FactureRepositoryInterface;
use App\Repositories\Interfaces\SoustraitantRepositoryInterface;
use App\Repositories\Interfaces\ProjetRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FactureSousTraitantService
{
    protected FactureRepositoryInterface $repository;
    protected SoustraitantRepositoryInterface $soustraitantRepository;
    protected ProjetRepositoryInterface $projetRepository;

    public function __construct(
        FactureRepositoryInterface $repository,
        SoustraitantRepositoryInterface $soustraitantRepository,
        ProjetRepositoryInterface $projetRepository
    ) {
        $this->repository = $repository;
        $this->soustraitantRepository = $soustraitantRepository;
        $this->projetRepository = $projetRepository;
    }

    public function getAll(): array
    {
        try {
            return $this->repository->getFacturesWithRelations();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des factures: ' . $e->getMessage());
            return [];
        }
    }

    public function getFacture(int $id): ?Facture
    {
        try {
            return $this->repository->withSupprime()
                ->with(['facturable', 'projet'])
                ->find($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la facture: ' . $e->getMessage());
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
                'emises' => 0,
                'payees' => 0,
                'partiellement' => 0,
                'annulees' => 0,
                'en_retard' => 0,
                'montant_total' => 0,
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

    public function getStatuts(): array
    {
        return Facture::getStatuts();
    }

    public function generateNumeroFacture(): string
    {
        return $this->repository->generateNumeroFacture();
    }

    public function create(array $data): ?Facture
    {
        try {
            // Définir le type et type_facturable
            $data['type'] = 'fournisseur';
            $data['type_facturable'] = 'Soustraitant';
            $data['etat'] = 1;
            $data['statut'] = $data['statut'] ?? 'emise';

            // Calculer le montant TTC
            $tva = $data['tva'] ?? 0;
            $data['montant_ttc'] = $data['montant_ht'] + ($data['montant_ht'] * $tva / 100);

            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la facture: ' . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            // Calculer le montant TTC si nécessaire
            if (isset($data['montant_ht'])) {
                $tva = $data['tva'] ?? 0;
                $data['montant_ttc'] = $data['montant_ht'] + ($data['montant_ht'] * $tva / 100);
            }

            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la facture: ' . $e->getMessage());
            return false;
        }
    }

    public function updateStatus(int $id, string $status): bool
    {
        try {
            return $this->repository->updateStatus($id, $status);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut: ' . $e->getMessage());
            return false;
        }
    }

    public function marquerPayee(int $id): bool
    {
        try {
            return $this->updateStatus($id, 'payee');
        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage payée: ' . $e->getMessage());
            return false;
        }
    }

    public function contester(int $id): bool
    {
        try {
            return $this->updateStatus($id, 'en_retard');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la contestation: ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la facture: ' . $e->getMessage());
            return false;
        }
    }

    public function restore(int $id): bool
    {
        try {
            return $this->repository->restore($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration de la facture: ' . $e->getMessage());
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

    public function getFacturesEnRetard(): array
    {
        try {
            return $this->repository->getFacturesEnRetard();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des factures en retard: ' . $e->getMessage());
            return [];
        }
    }
}
