<?php
namespace App\Domain\Finances\Repositories;

use App\Domain\Finances\Models\{EcritureComptable, PlanComptable};
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentEcritureComptableRepository extends BaseRepository implements EcritureComptableRepositoryInterface
{
    protected array $with = ['exercice', 'lignes'];
    protected array $colonnesSearch = ['numero', 'libelle'];
    protected array $filtresSimples = ['statut', 'exercice_fiscal_id'];
    protected string $orderBy = 'date_ecriture';

    public function __construct(EcritureComptable $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecLignes(EcritureComptable $ecriture): EcritureComptable
    {
        return $ecriture->load(['lignes.compte', 'exercice', 'validePar']);
    }

    public function brouillons(): Collection
    {
        return $this->newQuery()->where('statut', 'brouillon')->get();
    }

    public function validees(): Collection
    {
        return $this->newQuery()->where('statut', 'validee')->get();
    }

    public function parExercice(int $exerciceId): Collection
    {
        return $this->newQuery()->where('exercice_fiscal_id', $exerciceId)->get();
    }

    public function parPeriode(string $debut, string $fin): Collection
    {
        return $this->newQuery()->whereBetween('date_ecriture', [$debut, $fin])->get();
    }

    public function balance(int $exerciceId): Collection
    {
        return DB::table('ligne_ecriture_comptables as l')
            ->join('ecriture_comptables as e', 'l.ecriture_comptable_id', '=', 'e.id')
            ->join('plan_comptables as p', 'l.plan_comptable_id', '=', 'p.id')
            ->where('e.exercice_fiscal_id', $exerciceId)
            ->where('e.statut', 'validee')
            ->select(
                'p.numero',
                'p.libelle',
                DB::raw('SUM(l.debit) as total_debit'),
                DB::raw('SUM(l.credit) as total_credit')
            )
            ->groupBy('p.id', 'p.numero', 'p.libelle')
            ->orderBy('p.numero')
            ->get()
            ->map(function ($row) {
                $row->solde = (float) $row->total_debit - (float) $row->total_credit;
                return $row;
            });
    }

    public function grandLivre(int $compteId, int $exerciceId): Collection
    {
        return DB::table('ligne_ecriture_comptables as l')
            ->join('ecriture_comptables as e', 'l.ecriture_comptable_id', '=', 'e.id')
            ->where('l.plan_comptable_id', $compteId)
            ->where('e.exercice_fiscal_id', $exerciceId)
            ->where('e.statut', 'validee')
            ->select('e.date_ecriture', 'e.numero', 'e.libelle', 'l.debit', 'l.credit', 'l.libelle as ligne_libelle')
            ->orderBy('e.date_ecriture')
            ->get();
    }

    public function statistiques(?int $exerciceId = null): array
    {
        $q = $this->model->newQuery();
        if ($exerciceId) $q->where('exercice_fiscal_id', $exerciceId);

        return [
            'total'          => (clone $q)->count(),
            'brouillons'     => (clone $q)->where('statut', 'brouillon')->count(),
            'validees'       => (clone $q)->where('statut', 'validee')->count(),
            'total_debit'    => (float) DB::table('ligne_ecriture_comptables as l')
                ->join('ecriture_comptables as e', 'l.ecriture_comptable_id', '=', 'e.id')
                ->when($exerciceId, fn($qq) => $qq->where('e.exercice_fiscal_id', $exerciceId))
                ->where('e.statut', 'validee')
                ->sum('l.debit'),
            'total_credit'   => (float) DB::table('ligne_ecriture_comptables as l')
                ->join('ecriture_comptables as e', 'l.ecriture_comptable_id', '=', 'e.id')
                ->when($exerciceId, fn($qq) => $qq->where('e.exercice_fiscal_id', $exerciceId))
                ->where('e.statut', 'validee')
                ->sum('l.credit'),
        ];
    }
}