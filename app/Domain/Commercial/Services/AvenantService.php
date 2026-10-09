<?php
namespace App\Domain\Commercial\Services;

use App\Domain\Commercial\Models\AvenantMarche;
use App\Domain\Commercial\Repositories\AvenantMarcheRepositoryInterface;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AvenantService
{
    public function __construct(
        private AvenantMarcheRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    public function creer(array $data): AvenantMarche
    {
        return DB::transaction(function () use ($data) {
            $avenant = $this->repo->create($data);
            $this->journal->log('avenant.cree', $avenant);
            return $avenant;
        });
    }

    public function mettreAJour(AvenantMarche $avenant, array $data): AvenantMarche
    {
        return $this->repo->update($avenant, $data);
    }

    public function supprimer(AvenantMarche $avenant): bool
    {
        return $this->repo->desactiver($avenant);
    }

    public function parMarche(int $marcheId): Collection
    {
        return $this->repo->parMarche($marcheId);
    }
}