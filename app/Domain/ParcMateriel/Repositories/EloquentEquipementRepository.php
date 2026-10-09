<?php
namespace App\Domain\ParcMateriel\Repositories;

use App\Domain\ParcMateriel\Models\Equipement;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentEquipementRepository extends BaseRepository implements EquipementRepositoryInterface
{
    protected array $with = ['categorie', 'projetActuel'];
    protected array $colonnesSearch = ['nom', 'code', 'numero_immatriculation', 'marque'];
    protected array $filtresSimples = ['statut', 'categorie_id', 'propriete', 'projet_actuel_id'];

    public function __construct(Equipement $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecDetails(Equipement $equipement): Equipement
    {
        return $equipement->load([
            'categorie', 'projetActuel',
            'affectations.projet', 'maintenances', 'pannes', 'relevesCarburant', 'documents',
        ]);
    }

    public function parCategorie(int $categorieId): Collection
    {
        return $this->newQuery()->where('categorie_id', $categorieId)->get();
    }

    public function disponibles(): Collection
    {
        return $this->newQuery()->where('statut', 'disponible')->get();
    }

    public function enPanne(): Collection
    {
        return $this->newQuery()->where('statut', 'en_panne')->get();
    }

    public function avecDocumentsExpirant(int $jours = 30): Collection
    {
        return $this->newQuery()
            ->whereHas('documents', function ($q) use ($jours) {
                $q->whereNotNull('date_expiration')
                  ->whereDate('date_expiration', '<=', now()->addDays($jours));
            })
            ->get();
    }

    public function statistiques(): array
    {
        return [
            'total'          => $this->model->where('etat', 1)->count(),
            'disponibles'    => $this->model->where('statut', 'disponible')->count(),
            'en_service'     => $this->model->where('statut', 'en_service')->count(),
            'en_panne'       => $this->model->where('statut', 'en_panne')->count(),
            'en_maintenance' => $this->model->where('statut', 'en_maintenance')->count(),
            'hors_service'   => $this->model->where('statut', 'hors_service')->count(),
            'propres'        => $this->model->where('propriete', 'propre')->count(),
            'loues'          => $this->model->where('propriete', 'louee')->count(),
        ];
    }

    public function tauxDisponibilite(): float
    {
        $total = $this->model->where('etat', 1)->count();
        if ($total === 0) return 0;
        $disponibles = $this->model->where('statut', 'disponible')->count();
        return round(($disponibles / $total) * 100, 2);
    }

    public function coutTotalParc(): float
    {
        return (float) $this->model->where('etat', 1)->sum('valeur_actuelle');
    }
}