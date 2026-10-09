<?php
namespace App\Domain\Approvisionnement\Services;

use App\Domain\Approvisionnement\Models\Fournisseur;
use App\Domain\Approvisionnement\Repositories\FournisseurRepositoryInterface;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FournisseurService
{
    public function __construct(
        private FournisseurRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    public function creer(array $data): Fournisseur
    {
        $fournisseur = $this->repo->create($data);
        $this->journal->log('fournisseur.cree', $fournisseur);
        return $fournisseur;
    }

    public function mettreAJour(Fournisseur $fournisseur, array $data): Fournisseur
    {
        $fournisseur = $this->repo->update($fournisseur, $data);
        $this->journal->log('fournisseur.modifie', $fournisseur);
        return $fournisseur;
    }

    public function desactiver(Fournisseur $fournisseur): bool
    {
        return $this->repo->desactiver($fournisseur);
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function actifs(): Collection
    {
        return $this->repo->actifs();
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }
}