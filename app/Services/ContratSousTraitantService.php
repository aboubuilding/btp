<?php

namespace App\Services;

use App\Models\ContratSousTraitant;
use App\Repositories\Interfaces\ContratSousTraitantRepositoryInterface;
use App\Repositories\Interfaces\SoustraitantRepositoryInterface;
use App\Repositories\Interfaces\ProjetRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ContratSousTraitantService
{
    protected ContratSousTraitantRepositoryInterface $repository;
    protected SoustraitantRepositoryInterface $soustraitantRepository;
    protected ProjetRepositoryInterface $projetRepository;

    public function __construct(
        ContratSousTraitantRepositoryInterface $repository,
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
            return $this->repository->getContratsWithRelations();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des contrats: ' . $e->getMessage());
            return [];
        }
    }

    public function getContrat(int $id): ?ContratSousTraitant
    {
        try {
            return $this->repository->withSupprime()->with(['sousTraitant', 'projet'])->find($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du contrat: ' . $e->getMessage());
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
                'en_cours' => 0,
                'termines' => 0,
                'resilies' => 0,
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
        return ContratSousTraitant::getStatuts();
    }

    public function generateNumeroContrat(): string
    {
        return $this->repository->generateNumeroContrat();
    }

    public function create(array $data): ?ContratSousTraitant
    {
        try {
            $data['etat'] = 1;
            $data['statut'] = $data['statut'] ?? 'en_cours';
            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du contrat: ' . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du contrat: ' . $e->getMessage());
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

    public function delete(int $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du contrat: ' . $e->getMessage());
            return false;
        }
    }

    public function restore(int $id): bool
    {
        try {
            return $this->repository->restore($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration du contrat: ' . $e->getMessage());
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

    public function uploadFile($file, string $numeroContrat): ?string
    {
        try {
            $filename = $numeroContrat . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('contrats-sous-traitants', $filename, 'public');
            return $path;
        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement du fichier: ' . $e->getMessage());
            return null;
        }
    }

    public function getFileUrl(string $path): string
    {
        return Storage::url($path);
    }

    public function downloadFile(string $path)
    {
        try {
            return Storage::disk('public')->download($path);
        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement du fichier: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteFile(string $path): bool
    {
        try {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->delete($path);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du fichier: ' . $e->getMessage());
            return false;
        }
    }
}
