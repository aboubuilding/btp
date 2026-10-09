<?php
namespace App\Domain\Finances\Services;

use App\Domain\Finances\Models\Depense;
use App\Domain\Finances\Repositories\DepenseRepositoryInterface;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DepenseService
{
    public function __construct(
        private DepenseRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    public function creer(array $data, ?UploadedFile $justificatif = null, ?int $userId = null): Depense
    {
        if ($justificatif) {
            $data['document_justificatif'] = $justificatif->store('depenses', 'local');
        }
        $data['paye_par'] = $userId ?? auth()->id();
        $data['statut']   = 'en_attente';

        $depense = $this->repo->create($data);
        $this->journal->log('depense.creee', $depense);
        return $depense;
    }

    /**
     * RG-K04 : justificatif obligatoire pour approbation.
     */
    public function approuver(Depense $depense, int $userId): Depense
    {
        if (!$depense->document_justificatif) {
            throw new RegleGestionException('Un justificatif est obligatoire (RG-K04).');
        }

        return DB::transaction(function () use ($depense, $userId) {
            $depense = $this->repo->update($depense, [
                'statut'       => 'approuve',
                'approuve_par' => $userId,
                'approuve_le'  => now(),
            ]);

            // Imputation au budget réel du projet
            if ($depense->projet) {
                $depense->projet->increment('budget_reel', $depense->montant);
                $depense->ligneBudget?->increment('montant_reel', $depense->montant);
            }

            $this->journal->log('depense.approuvee', $depense);
            return $depense;
        });
    }

    public function rejeter(Depense $depense, int $userId): Depense
    {
        return $this->repo->update($depense, [
            'statut'       => 'rejete',
            'approuve_par' => $userId,
            'approuve_le'  => now(),
        ]);
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function enAttente(): Collection
    {
        return $this->repo->enAttente();
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function totalParProjet(int $projetId): float
    {
        return $this->repo->totalParProjet($projetId);
    }

    public function totalParCategorie(?int $projetId = null): array
    {
        return $this->repo->totalParCategorie($projetId);
    }
}