<?php
namespace App\Domain\Commercial\Services;

use App\Domain\Commercial\Models\Devis;
use App\Domain\Commercial\Repositories\DevisRepositoryInterface;
use App\Domain\Commercial\Events\DevisAccepte;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\{JournalService, ParametreService, ReferenceService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DevisService
{
    public function __construct(
        private DevisRepositoryInterface $repo,
        private JournalService $journal,
        private ParametreService $params,
        private ReferenceService $reference,
    ) {}

    /**
     * Crée un devis avec ses lignes (transaction).
     * RG-C01 : Un devis accepté n'est plus modifiable.
     */
    public function creer(array $data, array $lignes = []): Devis
    {
        return DB::transaction(function () use ($data, $lignes) {
            $data['numero']   = $data['numero']   ?? $this->reference->devis();
            $data['taux_tva'] = $data['taux_tva'] ?? $this->params->getTauxTva();
            $data['statut']   = 'brouillon';

            $devis = $this->repo->create($data);
            $this->synchroniserLignes($devis, $lignes);
            $devis->recalculerTotaux();

            $this->journal->log('devis.cree', $devis);
            return $devis->fresh(['lignes']);
        });
    }

    /**
     * Met à jour un devis et ses lignes.
     */
    public function mettreAJour(Devis $devis, array $data, array $lignes = []): Devis
    {
        if ($devis->statut === 'accepte') {
            throw new RegleGestionException('Un devis accepté n\'est plus modifiable (RG-C01).');
        }

        return DB::transaction(function () use ($devis, $data, $lignes) {
            $devis = $this->repo->update($devis, $data);

            if (!empty($lignes)) {
                $devis->lignes()->delete();
                $this->synchroniserLignes($devis, $lignes);
            }

            $devis->recalculerTotaux();
            $this->journal->log('devis.modifie', $devis);
            return $devis->fresh(['lignes']);
        });
    }

    /**
     * Soumet le devis au client.
     */
    public function envoyer(Devis $devis): Devis
    {
        if ($devis->statut !== 'brouillon') {
            throw new RegleGestionException('Seul un devis en brouillon peut être envoyé.');
        }
        $devis = $this->repo->update($devis, ['statut' => 'envoye']);
        $this->journal->log('devis.envoye', $devis);
        return $devis;
    }

    /**
     * Marque le devis comme accepté.
     */
    public function accepter(Devis $devis): Devis
    {
        if ($devis->statut !== 'envoye') {
            throw new RegleGestionException('Seul un devis envoyé peut être accepté.');
        }

        return DB::transaction(function () use ($devis) {
            $devis = $this->repo->update($devis, [
                'statut'    => 'accepte',
                'valide_le' => now(),
            ]);

            event(new DevisAccepte($devis));
            $this->journal->log('devis.accepte', $devis);
            return $devis;
        });
    }

    public function refuser(Devis $devis): Devis
    {
        return $this->repo->update($devis, ['statut' => 'refuse']);
    }

    public function dupliquer(Devis $source): Devis
    {
        return DB::transaction(function () use ($source) {
            $nouveau = $source->replicate();
            $nouveau->numero = $this->reference->devis();
            $nouveau->statut = 'brouillon';
            $nouveau->date_devis = now();
            $nouveau->valide_le = null;
            $nouveau->save();

            foreach ($source->lignes as $ligne) {
                $nouveau->lignes()->create($ligne->replicate()->toArray());
            }

            $nouveau->recalculerTotaux();
            $this->journal->log('devis.duplique', $nouveau, null, null, ['source' => $source->id]);
            return $nouveau->fresh(['lignes']);
        });
    }

    public function supprimer(Devis $devis): bool
    {
        if ($devis->statut === 'accepte') {
            throw new RegleGestionException('Impossible de supprimer un devis accepté.');
        }
        $this->journal->log('devis.supprime', $devis);
        return $this->repo->desactiver($devis);
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecClient($filtres, $parPage);
    }

    public function avecLignes(Devis $devis): Devis
    {
        return $this->repo->avecLignes($devis);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function acceptes(): Collection
    {
        return $this->repo->acceptes();
    }

    private function synchroniserLignes(Devis $devis, array $lignes): void
    {
        foreach ($lignes as $ligne) {
            $ligne['montant'] = round((float) $ligne['quantite'] * (float) $ligne['prix_unitaire'], 2);
            $devis->lignes()->create($ligne);
        }
    }
}