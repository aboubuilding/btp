<?php
namespace App\Domain\Approvisionnement\Services;

use App\Domain\Approvisionnement\Models\BonCommande;
use App\Domain\Approvisionnement\Repositories\BonCommandeRepositoryInterface;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\{JournalService, ParametreService, ReferenceService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BonCommandeService
{
    public function __construct(
        private BonCommandeRepositoryInterface $repo,
        private JournalService $journal,
        private ParametreService $params,
        private ReferenceService $reference,
    ) {}

    public function creer(array $data, array $articles, ?int $userId = null): BonCommande
    {
        return DB::transaction(function () use ($data, $articles, $userId) {
            $data['numero'] = $data['numero'] ?? $this->reference->bonCommande();
            $data['statut'] = 'brouillon';

            $bc = $this->repo->create($data);

            foreach ($articles as $article) {
                $article['montant'] = round((float) $article['quantite'] * (float) $article['prix_unitaire'], 2);
                $bc->articles()->create($article);
            }

            $bc->recalculerTotal();
            $this->journal->log('bon_commande.cree', $bc);
            return $bc->fresh(['articles']);
        });
    }

    public function envoyer(BonCommande $bc): BonCommande
    {
        if ($bc->statut !== 'brouillon') {
            throw new RegleGestionException('Seul un BC en brouillon peut être envoyé.');
        }
        $bc = $this->repo->update($bc, ['statut' => 'envoye']);
        $this->journal->log('bon_commande.envoye', $bc);
        return $bc;
    }

    public function confirmer(BonCommande $bc): BonCommande
    {
        if ($bc->statut !== 'envoye') {
            throw new RegleGestionException('Seul un BC envoyé peut être confirmé.');
        }
        return $this->repo->update($bc, ['statut' => 'confirme']);
    }

    /**
     * RG-BC01 : Validation DG au-delà du seuil.
     */
    public function validerDirection(BonCommande $bc, int $userId): BonCommande
    {
        $bc = $this->repo->update($bc, [
            'valide_par' => $userId,
            'valide_le'  => now(),
        ]);
        $this->journal->log('bon_commande.valide_dg', $bc);
        return $bc;
    }

    public function annuler(BonCommande $bc): BonCommande
    {
        if ($bc->est_fige) {
            throw new RegleGestionException('Impossible d\'annuler un BC livré ou déjà annulé.');
        }
        return $this->repo->update($bc, ['statut' => 'annule']);
    }

    public function necessiteValidationDG(BonCommande $bc): bool
    {
        return (float) $bc->montant_total >= $this->params->getSeuilValidationDG('bc');
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(BonCommande $bc): BonCommande
    {
        return $this->repo->avecDetails($bc);
    }

    public function enCours(): Collection
    {
        return $this->repo->enCours();
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }
}