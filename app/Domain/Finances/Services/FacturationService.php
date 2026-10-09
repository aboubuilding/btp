<?php
namespace App\Domain\Finances\Services;

use App\Domain\Finances\Models\{Facture, Paiement};
use App\Domain\Finances\Repositories\FactureRepositoryInterface;
use App\Domain\Finances\Events\{FactureEmise, PaiementEnregistre};
use App\Domain\Socle\Services\{JournalService, ReferenceService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FacturationService
{
    public function __construct(
        private FactureRepositoryInterface $repo,
        private JournalService $journal,
        private ReferenceService $reference,
    ) {}

    public function creer(array $data): Facture
    {
        return DB::transaction(function () use ($data) {
            $data['numero_facture'] = $data['numero_facture'] ?? $this->reference->facture();
            $data['statut']         = $data['statut'] ?? 'emise';

            $facture = $this->repo->create($data);
            event(new FactureEmise($facture));
            $this->journal->log('facture.creee', $facture);

            return $facture;
        });
    }

    public function enregistrerPaiement(Facture $facture, array $data): Paiement
    {
        return DB::transaction(function () use ($facture, $data) {
            $data['numero_paiement'] = $data['numero_paiement'] ?? $this->reference->paiement();
            $data['type_payable']    = Facture::class;
            $data['id_payable']      = $facture->id;

            $paiement = $facture->paiements()->create($data);

            $facture->increment('montant_paye', $data['montant']);
            $facture->recalculerStatut();

            event(new PaiementEnregistre($paiement));
            $this->journal->log('paiement.enregistre', $paiement);

            return $paiement;
        });
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(Facture $facture): Facture
    {
        return $this->repo->avecDetails($facture);
    }

    public function impayees(): Collection
    {
        return $this->repo->impayees();
    }

    public function enRetard(): Collection
    {
        return $this->repo->enRetard();
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function chiffreAffaire(string $debut, string $fin): float
    {
        return $this->repo->chiffreAffaire($debut, $fin);
    }
}