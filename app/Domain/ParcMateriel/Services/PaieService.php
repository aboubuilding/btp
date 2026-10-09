<?php
namespace App\Domain\Personnel\Services;

use App\Domain\Personnel\Models\{PeriodePaie, BulletinPaie, Presence, AvanceSalaire, Employe};
use App\Domain\Personnel\Repositories\{PeriodePaieRepositoryInterface, BulletinPaieRepositoryInterface};
use App\Domain\Personnel\Events\PeriodePaieCloturee;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\{JournalService, ParametreService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaieService
{
    public function __construct(
        private PeriodePaieRepositoryInterface $periodeRepo,
        private BulletinPaieRepositoryInterface $bulletinRepo,
        private JournalService $journal,
        private ParametreService $params,
    ) {}

    /**
     * RG-P02 : seuls les pointages validés sont pris en compte.
     */
    public function genererBulletins(PeriodePaie $periode): int
    {
        if ($periode->statut !== 'ouverte') {
            throw new RegleGestionException('Période déjà clôturée.');
        }

        return DB::transaction(function () use ($periode) {
            $employes = Employe::where('statut', 'actif')->where('etat', 1)->get();
            $tauxCs = $this->params->getCotisationsSalariales();
            $tauxCp = $this->params->getCotisationsPatronales();
            $tauxIr = $this->params->getTauxImpot();
            $count = 0;

            foreach ($employes as $employe) {
                $heures = Presence::validees()
                    ->where('employee_id', $employe->id)
                    ->whereBetween('date', [$periode->date_debut, $periode->date_fin])
                    ->sum('heures_travaillees');

                if ($heures <= 0 && $employe->type_contrat !== 'cdi') continue;

                $brut = (float) $employe->salaire_base;
                $cs = round($brut * $tauxCs / 100, 2);
                $cp = round($brut * $tauxCp / 100, 2);
                $ir = round(($brut - $cs) * $tauxIr / 100, 2);

                // RG-P04 : avances non remboursées
                $avances = AvanceSalaire::where('employee_id', $employe->id)
                    ->where('statut', 'approuvee')
                    ->sum(DB::raw('montant - montant_rembourse'));

                BulletinPaie::updateOrCreate(
                    ['periode_paie_id' => $periode->id, 'employee_id' => $employe->id],
                    [
                        'salaire_base'            => $employe->salaire_base,
                        'heures_travaillees'      => $heures,
                        'brut'                    => $brut,
                        'cotisations_salariales'  => $cs,
                        'cotisations_patronales'  => $cp,
                        'impot_revenu'            => $ir,
                        'avances_deduites'        => $avances,
                        'net_a_payer'             => round($brut - $cs - $ir - $avances, 2),
                        'statut'                  => 'brouillon',
                    ]
                );
                $count++;
            }

            $this->journal->log('paie.bulletins_generes', $periode, null, null, ['count' => $count]);
            return $count;
        });
    }

    /**
     * RG-P03 : clôture = période figée.
     */
    public function cloturer(PeriodePaie $periode, int $userId): void
    {
        if ($periode->statut !== 'ouverte') {
            throw new RegleGestionException('Période déjà clôturée (RG-P03).');
        }

        DB::transaction(function () use ($periode, $userId) {
            $periode->update([
                'statut'      => 'cloturee',
                'cloture_par' => $userId,
                'cloture_le'  => now(),
            ]);

            event(new PeriodePaieCloturee($periode));
            $this->journal->log('paie.periode_cloturee', $periode);
        });
    }

    public function creerPeriode(array $data): PeriodePaie
    {
        $data['statut'] = 'ouverte';
        return $this->periodeRepo->create($data);
    }

    public function paginatePeriodes(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->periodeRepo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecBulletins(PeriodePaie $periode): PeriodePaie
    {
        return $this->periodeRepo->avecBulletins($periode);
    }

    public function masseSalariale(PeriodePaie $periode): array
    {
        return $this->bulletinRepo->masseSalarialePeriode($periode->id);
    }
}