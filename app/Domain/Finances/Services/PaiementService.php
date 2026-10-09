<?php
namespace App\Domain\Finances\Services;

use App\Domain\Finances\Models\Paiement;
use App\Domain\Finances\Repositories\PaiementRepositoryInterface;
use App\Domain\Socle\Services\{JournalService, ReferenceService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PaiementService
{
    public function __construct(
        private PaiementRepositoryInterface $repo,
        private TresorerieService $tresorerie,
        private JournalService $journal,
        private ReferenceService $reference,
    ) {}

    public function enregistrer(array $data): Paiement
    {
        return DB::transaction(function () use ($data) {
            $data['numero_paiement'] = $data['numero_paiement'] ?? $this->reference->paiement();

            $paiement = $this->repo->create($data);

            // Mise à jour des soldes (caisse / banque)
            $this->tresorerie->enregistrerPaiement($paiement);

            $this->journal->log('paiement.enregistre', $paiement);
            return $paiement;
        });
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function encaissements(string $debut, string $fin): Collection
    {
        return $this->repo->parPeriode($debut, $fin)->where('sens', 'encaissement');
    }

    public function decaissements(string $debut, string $fin): Collection
    {
        return $this->repo->parPeriode($debut, $fin)->where('sens', 'decaissement');
    }
}