<?php
namespace App\Domain\Commercial\Services;

use App\Domain\Commercial\Models\{Marche, AvenantMarche};
use App\Domain\Commercial\Repositories\{MarcheRepositoryInterface, AvenantMarcheRepositoryInterface, CautionMarcheRepositoryInterface};
use App\Domain\Commercial\Events\MarcheSigne;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\{JournalService, ReferenceService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MarcheService
{
    public function __construct(
        private MarcheRepositoryInterface $repo,
        private AvenantMarcheRepositoryInterface $avenantRepo,
        private CautionMarcheRepositoryInterface $cautionRepo,
        private JournalService $journal,
        private ReferenceService $reference,
    ) {}

    public function creer(array $data): Marche
    {
        return DB::transaction(function () use ($data) {
            $data['reference'] = $data['reference'] ?? $this->reference->marche();
            $marche = $this->repo->create($data);
            $this->journal->log('marche.cree', $marche);
            return $marche;
        });
    }

    public function mettreAJour(Marche $marche, array $data): Marche
    {
        if ($marche->statut === 'clos') {
            throw new RegleGestionException('Un marché clos n\'est plus modifiable.');
        }
        $marche = $this->repo->update($marche, $data);
        $this->journal->log('marche.modifie', $marche);
        return $marche;
    }

    /**
     * Signature du marché : déclenche événement.
     */
    public function signer(Marche $marche): Marche
    {
        if ($marche->est_signe) {
            throw new RegleGestionException('Marché déjà signé.');
        }

        return DB::transaction(function () use ($marche) {
            $marche = $this->repo->update($marche, [
                'statut'         => 'signe',
                'date_signature' => $marche->date_signature ?? now(),
            ]);

            event(new MarcheSigne($marche));
            $this->journal->log('marche.signe', $marche);
            return $marche;
        });
    }

    public function ajouterAvenant(Marche $marche, array $data): AvenantMarche
    {
        if ($marche->statut === 'clos') {
            throw new RegleGestionException('Impossible d\'ajouter un avenant à un marché clos.');
        }

        return DB::transaction(function () use ($marche, $data) {
            $data['numero'] = $data['numero'] ?? $this->avenantRepo->genererNumero($marche->id);
            $avenant = $this->avenantRepo->create(array_merge($data, ['marche_id' => $marche->id]));
            $this->journal->log('avenant.cree', $avenant);
            return $avenant;
        });
    }

    public function signerAvenant(AvenantMarche $avenant): AvenantMarche
    {
        return DB::transaction(function () use ($avenant) {
            $avenant = $this->avenantRepo->update($avenant, [
                'est_signe'      => true,
                'date_signature' => now(),
            ]);
            $this->journal->log('avenant.signe', $avenant);
            return $avenant;
        });
    }

    public function montantActualise(Marche $marche): float
    {
        return (float) $marche->montant_initial
             + $this->avenantRepo->montantSignesParMarche($marche->id);
    }

    public function soldeAvanceRestant(Marche $marche): float
    {
        $avanceInitiale = (float) $marche->montant_initial * ($marche->taux_avance / 100);

        $dejaRembourse = (float) \App\Domain\Execution\Models\Situation::whereIn(
            'projet_id', $marche->projets()->pluck('id')
        )->whereIn('statut', ['validee', 'transmise', 'approuvee', 'facturee'])
         ->sum('remboursement_avance');

        return max(0, $avanceInitiale - $dejaRembourse);
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(Marche $marche): Marche
    {
        return $this->repo->avecDetails($marche);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function montantTotalPortefeuille(): float
    {
        return $this->repo->montantTotalPortefeuille();
    }

    public function cautionsExpirantBientot(int $jours = 30): Collection
    {
        return $this->cautionRepo->expirentBientot($jours);
    }
}