<?php
namespace App\Domain\Finances\Services;

use App\Domain\Finances\Models\EcritureComptable;
use App\Domain\Finances\Repositories\EcritureComptableRepositoryInterface;
use App\Domain\Socle\Services\JournalService;

class EcritureService
{
    public function __construct(
        private EcritureComptableRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    public function paginate(array $filtres = [], int $parPage = 25)
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecLignes(EcritureComptable $ecriture): EcritureComptable
    {
        return $this->repo->avecLignes($ecriture);
    }

    public function brouillons()
    {
        return $this->repo->brouillons();
    }

    public function validees()
    {
        return $this->repo->validees();
    }
}