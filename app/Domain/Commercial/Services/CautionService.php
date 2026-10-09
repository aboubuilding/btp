<?php
namespace App\Domain\Commercial\Services;

use App\Domain\Commercial\Models\CautionMarche;
use App\Domain\Commercial\Repositories\CautionMarcheRepositoryInterface;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CautionService
{
    public function __construct(
        private CautionMarcheRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    public function creer(array $data): CautionMarche
    {
        return DB::transaction(function () use ($data) {
            $caution = $this->repo->create($data);
            $this->journal->log('caution.creee', $caution);
            return $caution;
        });
    }

    public function mettreAJour(CautionMarche $caution, array $data): CautionMarche
    {
        return $this->repo->update($caution, $data);
    }

    public function supprimer(CautionMarche $caution): bool
    {
        return $this->repo->desactiver($caution);
    }

    public function expirantBientot(int $jours = 30): Collection
    {
        return $this->repo->expirentBientot($jours);
    }

    public function montantTotalActif(int $marcheId): float
    {
        return $this->repo->montantTotalActif($marcheId);
    }
}