<?php
namespace App\Domain\Socle\Repositories;

use App\Domain\Socle\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected array $colonnesSearch = ['nom', 'email', 'telephone'];
    protected array $with = ['role'];
    protected array $filtresSimples = ['role_id', 'est_actif'];

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->newQuery()->where('email', $email)->first();
    }

    public function parRole(string $slug): Collection
    {
        return $this->newQuery()
            ->whereHas('role', fn($q) => $q->where('slug', $slug))
            ->where('est_actif', true)
            ->get();
    }

    public function pourDirection(): Collection
    {
        return $this->newQuery()
            ->whereHas('role', fn($q) => $q->whereIn('slug', ['direction', 'directeur_technique', 'admin']))
            ->where('est_actif', true)
            ->get();
    }

    public function actifs(): Collection
    {
        return $this->newQuery()->where('est_actif', true)->where('etat', 1)->get();
    }

    public function paginateAvecRole(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function creerAvecRole(array $data, string $motDePasse): User
    {
        $data['mot_de_passe'] = Hash::make($motDePasse);
        return $this->create($data);
    }

    public function mettreAJourMotDePasse(User $user, string $motDePasse): bool
    {
        return $user->update(['mot_de_passe' => Hash::make($motDePasse)]);
    }

    public function enregistrerConnexion(User $user, string $ip): void
    {
        $user->update([
            'derniere_connexion_le' => now(),
            'derniere_connexion_ip' => $ip,
        ]);
    }
}