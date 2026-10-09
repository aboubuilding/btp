<?php
namespace App\Domain\Approvisionnement\Services;

use App\Domain\Approvisionnement\Models\{Livraison, BonCommande};
use App\Domain\Approvisionnement\Repositories\LivraisonRepositoryInterface;
use App\Domain\Approvisionnement\Events\LivraisonReceptionnee;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\{JournalService, ReferenceService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LivraisonService
{
    public function __construct(
        private LivraisonRepositoryInterface $repo,
        private JournalService $journal,
        private ReferenceService $reference,
    ) {}

    /**
     * RG-A03 : Réception ≤ quantité commandée.
     */
    public function creer(array $data, array $articles, ?int $userId = null): Livraison
    {
        $bonCommande = BonCommande::findOrFail($data['bon_commande_id']);

        if ($bonCommande->est_fige) {
            throw new RegleGestionException('Bon de commande déjà clôturé.');
        }

        return DB::transaction(function () use ($data, $articles, $userId, $bonCommande) {
            $data['numero'] = $data['numero'] ?? $this->reference->livraison();
            $data['statut'] = 'partielle';

            $livraison = $this->repo->create($data);

            foreach ($articles as $articleData) {
                $ligneBC = $bonCommande->articles()
                    ->where('materiau_id', $articleData['materiau_id'])
                    ->first();

                if (!$ligneBC) {
                    throw new RegleGestionException('Matériau non commandé dans ce BC.');
                }

                if ((float) $articleData['quantite_recue'] > (float) $ligneBC->reste_a_livrer) {
                    throw new RegleGestionException(
                        "Quantité reçue supérieure au reste à livrer pour {$ligneBC->designation} (RG-A03)."
                    );
                }

                $livraison->articles()->create(array_merge($articleData, [
                    'quantite_commandee' => $ligneBC->quantite,
                ]));
            }

            // Détermine le statut global de la livraison
            $toutRecu = $livraison->articles->every(
                fn($a) => (float) $a->quantite_recue >= (float) $a->quantite_commandee
            );
            $livraison->update(['statut' => $toutRecu ? 'complete' : 'partielle']);

            event(new LivraisonReceptionnee($livraison));

            $this->journal->log('livraison.creee', $livraison);
            return $livraison->fresh(['articles.materiau']);
        });
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(Livraison $livraison): Livraison
    {
        return $this->repo->avecDetails($livraison);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }
}