<?php
namespace App\Domain\Execution\Services;

use App\Domain\Execution\Models\{Attachement, Situation, Projet};
use App\Domain\Commercial\Models\LigneDevis;
use App\Domain\Commercial\Services\MarcheService;
use App\Domain\Execution\Repositories\SituationRepositoryInterface;
use App\Domain\Execution\Events\SituationApprouvee;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\{JournalService, ParametreService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SituationService
{
    public function __construct(
        private SituationRepositoryInterface $repo,
        private MarcheService $marcheService,
        private ParametreService $params,
        private JournalService $journal,
    ) {}

    /**
     * Crée une situation à partir d'un attachement validé (RG-F01).
     */
    public function creer(Projet $projet, Attachement $attachement): Situation
    {
        if ($attachement->statut !== 'valide') {
            throw new RegleGestionException('L\'attachement doit être validé.');
        }
        if ($attachement->projet_id !== $projet->id) {
            throw new RegleGestionException('Attachement d\'un autre chantier.');
        }

        $marche = $projet->marche;
        if (!$marche) {
            throw new RegleGestionException('Aucun marché rattaché au chantier.');
        }

        return DB::transaction(function () use ($projet, $attachement, $marche) {
            $derniere = $this->repo->dernierePourProjet($projet->id);
            $numero   = $derniere ? $derniere->numero + 1 : 1;

            // RG-F01 : cumul ≤ quantité marché (augmentée des avenants)
            $montantCumule = 0;
            foreach ($attachement->lignes as $ligneAtt) {
                $ligneDevis = LigneDevis::findOrFail($ligneAtt->ligne_devis_id);
                $quantiteMax = (float) $ligneDevis->quantite;

                if ((float) $ligneAtt->quantite_cumulee > $quantiteMax) {
                    throw new RegleGestionException(
                        "Dépassement quantité ligne {$ligneDevis->numero_prix} (RG-F01). "
                        . "Validation du Directeur Technique requise."
                    );
                }

                $montantCumule += (float) $ligneAtt->quantite_cumulee * (float) $ligneDevis->prix_unitaire;
            }

            $situation = $this->repo->create([
                'projet_id'             => $projet->id,
                'attachement_id'        => $attachement->id,
                'numero'                => $numero,
                'periode_debut'         => $attachement->periode_debut,
                'periode_fin'           => $attachement->periode_fin,
                'montant_cumule_ht'     => $montantCumule,
                'montant_precedent_ht'  => $derniere?->montant_cumule_ht ?? 0,
                'statut'                => 'brouillon',
            ]);

            $soldeAvance = $this->marcheService->soldeAvanceRestant($marche);

            $situation->calculerMontants(
                (float) $marche->taux_retenue_garantie,
                $this->params->getTauxTva(),
                $soldeAvance
            );
            $situation->save();

            $this->journal->log('situation.creee', $situation, null, null, ['numero' => $numero]);
            return $situation->fresh();
        });
    }

    public function valider(Situation $situation, int $userId): Situation
    {
        if ($situation->statut !== 'brouillon') {
            throw new RegleGestionException('Situation déjà traitée.');
        }

        $situation = $this->repo->update($situation, [
            'statut'    => 'validee',
            'valide_par'=> $userId,
            'valide_le' => now(),
        ]);
        $this->journal->log('situation.validee', $situation);
        return $situation;
    }

    public function transmettre(Situation $situation): Situation
    {
        if ($situation->statut !== 'validee') {
            throw new RegleGestionException('Seule une situation validée peut être transmise.');
        }
        $situation = $this->repo->update($situation, ['statut' => 'transmise']);
        $this->journal->log('situation.transmise', $situation);
        return $situation;
    }

    public function approuver(Situation $situation): Situation
    {
        if ($situation->statut !== 'transmise') {
            throw new RegleGestionException('Seule une situation transmise peut être approuvée.');
        }

        return DB::transaction(function () use ($situation) {
            $situation = $this->repo->update($situation, ['statut' => 'approuvee']);
            event(new SituationApprouvee($situation));
            $this->journal->log('situation.approuvee', $situation);
            return $situation;
        });
    }

    public function marquerFacturee(Situation $situation): Situation
    {
        return $this->repo->update($situation, ['statut' => 'facturee']);
    }

    public function supprimer(Situation $situation): bool
    {
        if ($situation->statut !== 'brouillon') {
            throw new RegleGestionException('Seules les situations en brouillon sont supprimables.');
        }
        return $this->repo->desactiver($situation);
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(Situation $situation): Situation
    {
        return $this->repo->avecDetails($situation);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function chiffreAffaireFacture(?int $projetId = null): float
    {
        return $this->repo->chiffreAffaireFacture($projetId);
    }

    public function approuveesNonFacturees(): Collection
    {
        return $this->repo->approuveesNonFacturees();
    }
}