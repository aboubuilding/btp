<?php
namespace App\Domain\Personnel\Repositories;

use App\Domain\Personnel\Models\Employe;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentEmployeRepository extends BaseRepository implements EmployeRepositoryInterface
{
    protected array $with = ['poste', 'departement'];
    protected array $colonnesSearch = ['nom', 'prenom', 'matricule', 'telephone', 'numero_cnss'];
    protected array $filtresSimples = ['statut', 'type_contrat', 'departement_id', 'poste_id'];

    public function __construct(Employe $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecDetails(Employe $employe): Employe
    {
        return $employe->load([
            'poste', 'departement', 'contrats',
            'conges.typeConge', 'documents',
            'bulletins.periode', 'projets',
        ]);
    }

    public function findByMatricule(string $matricule): ?Employe
    {
        return $this->newQuery()->where('matricule', $matricule)->first();
    }

    public function actifs(): Collection
    {
        return $this->newQuery()->where('statut', 'actif')->where('etat', 1)->get();
    }

    public function journaliers(): Collection
    {
        return $this->newQuery()
            ->where('type_contrat', 'journalier')
            ->where('statut', 'actif')
            ->where('etat', 1)
            ->get();
    }

    public function permanents(): Collection
    {
        return $this->newQuery()
            ->whereIn('type_contrat', ['cdi', 'cdd'])
            ->where('statut', 'actif')
            ->where('etat', 1)
            ->get();
    }

    public function parDepartement(int $departementId): Collection
    {
        return $this->newQuery()->where('departement_id', $departementId)->where('etat', 1)->get();
    }

    public function parPoste(int $posteId): Collection
    {
        return $this->newQuery()->where('poste_id', $posteId)->where('etat', 1)->get();
    }

    public function parChantier(int $projetId): Collection
    {
        return $this->newQuery()
            ->whereHas('projets', fn($q) => $q->where('projet_id', $projetId))
            ->where('etat', 1)
            ->get();
    }

    public function statistiques(): array
    {
        $total = $this->model->where('etat', 1)->count();
        return [
            'total'         => $total,
            'actifs'        => $this->model->where('statut', 'actif')->where('etat', 1)->count(),
            'journaliers'   => $this->model->where('type_contrat', 'journalier')->count(),
            'cdi'           => $this->model->where('type_contrat', 'cdi')->count(),
            'cdd'           => $this->model->where('type_contrat', 'cdd')->count(),
            'stagiaires'    => $this->model->where('type_contrat', 'stage')->count(),
            'par_departement' => $this->model->where('etat', 1)
                ->selectRaw('departement_id, COUNT(*) as total')
                ->groupBy('departement_id')
                ->pluck('total', 'departement_id')
                ->toArray(),
        ];
    }

    public function masseSalariale(?int $departementId = null): float
    {
        $q = $this->model->where('statut', 'actif')->where('etat', 1);
        if ($departementId) $q->where('departement_id', $departementId);
        return (float) $q->sum('salaire_base');
    }

    public function genererMatricule(): string
    {
        $last = $this->model->max('id') ?? 0;
        return 'EMP-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}